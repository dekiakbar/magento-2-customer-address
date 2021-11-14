<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Csv extends AbstractHelper
{
    const CSV_FOLDER = "Files";
    const ALLOWED_FILE_EXTENSION = ".csv";
    protected $csv;
    protected $moduleReader;
    protected $driverFile;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\File\Csv $csv
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        parent::__construct($context);
        $this->csv = $csv;
        $this->moduleReader = $moduleReader;
        $this->driverFile = $driverFile;
    }

    /**
     * Get Root path for CSV file
     *
     * @return string
     */
    public function getFilesDirectory()
    {
        $viewDir = $this->moduleReader->getModuleDir(
            null,
            'Deki_CustomerAddress'
        );
        
        return $viewDir.'/'.self::CSV_FOLDER;
    }

    /**
     * Get full path for available region
     *
     * @return array
     */
    public function getFileFullPathList()
    {
        return $this->driverFile->readDirectory($this->getFilesDirectory());
    }

    /**
     * Get all available region csv file
     *
     * @return array
     */
    public function getRegionList()
    {
        $paths = $this->getFileFullPathList();
        $files = [];
        foreach($paths as $path){
            if (strpos(strtolower($path), self::ALLOWED_FILE_EXTENSION) !== false) {
                $partsOfPath = explode('/', str_replace('\\', '/', $path));
                $fileName = $partsOfPath[count($partsOfPath) - 1];
                $files[] = basename($fileName, '.csv');
            }
        }

        return $files;
    }
}

