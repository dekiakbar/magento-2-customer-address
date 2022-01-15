<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegionList extends Command
{
    protected $csvHelper;

    public function __construct(
        \Deki\CustomerAddress\Helper\Csv $csvHelper
    ) {
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
        try {
            $files = $this->csvHelper->getRegionList();
            if (count($files) > 0) {
                $output->writeln("<info>Available CSV file :</info>");
                foreach ($files as $file) {
                    $output->writeln("- ".$file);
                }
                $output->writeln("use available region list for import, example :");
                $output->writeln("<info>php bin/magento customer-address:import-region ID</info>");
            } else {
                $output->writeln("<error>No CSV file found in : ".$this->csvHelper->getFilesDirectory()."</error>");
            }
        } catch (\Exception $e) {
            $output->writeln("<error>Spmething wrong happened: ".$e->getMessage()."</error>");
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("customer-address:region-list");
        $this->setDescription("Show availabe region data");
        parent::configure();
    }
}
