<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;

class ImportRegion extends Command
{
    const INPUT_REGION_KEY = 'region';
    protected $csvHelper;

    public function __construct(
        \Deki\CustomerAddress\Helper\Csv $csvHelper
    ){
        $this->csvHelper = $csvHelper;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $returnValue = Cli::RETURN_SUCCESS;
        foreach ($this->getRegions($input) as $region) {
            try {
                $startTime = microtime(true);
                
                // do your magic
                sleep(2);
                $output->writeln($region);

                $resultTime = microtime(true) - $startTime;
                $output->writeln(
                    __('has been rebuilt successfully in %time', ['time' => gmdate('H:i:s', (int) $resultTime)])
                );
            } catch (\Throwable $e) {
                $output->writeln('process error during importing process:');
                $output->writeln($e->getMessage());

                $output->writeln($e->getTraceAsString(), OutputInterface::VERBOSITY_DEBUG);
                $returnValue = Cli::RETURN_FAILURE;
            }
        }

        return $returnValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("customer-address:region-import");
        $this->setDescription("Import available region");
        $this->setDefinition([
            new InputArgument(
                self::INPUT_REGION_KEY,
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Space-separated list of region'
            )
        ]);
        parent::configure();
    }

    /**
     * Returns the ordered list of regions.
     *
     * @param InputInterface $input
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function getRegions(InputInterface $input)
    {
        $requestedTypes = [];
        if ($input->getArgument(self::INPUT_REGION_KEY)) {
            $requestedTypes = $input->getArgument(self::INPUT_REGION_KEY);
            $requestedTypes = array_filter(array_map('trim', $requestedTypes), 'strlen');
        }

        if (empty($requestedTypes)) {
            $regions = $this->csvHelper->getRegionList();
        } else {
            $availableRegions = $this->csvHelper->getRegionList();
            $unsupportedTypes = array_diff($requestedTypes, $availableRegions);
            if ($unsupportedTypes) {
                throw new \InvalidArgumentException(
                    "The following requested region are not supported: '" . join("', '", $unsupportedTypes)
                    . "'." . PHP_EOL . 'Supported types: ' . join(", ", $availableRegions)
                );
            }
            $regions = array_intersect_key($availableRegions, array_flip($requestedTypes));
        }

        return $regions;
    }
}
