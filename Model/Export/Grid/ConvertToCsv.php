<?php
/**
 * Copyright © Deki. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Deki\CustomerAddress\Model\Export\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Ui\Component\MassAction\Filter;
use Deki\CustomerAddress\Model\ResourceModel\City;
use \Magento\Directory\Model\RegionFactory;

/**
 * Class ConvertToCsv
 * Convert data to csv file
 */
class ConvertToCsv
{
    /**
     * @var DirectoryList
     */
    protected $directory;

    /**
     * @var int|null
     */
    protected $pageSize = null;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var City
     */
    protected $cityResource;

    /**
     * @var Region
     */
    protected $regionFactory;

    /**
     * Constructor
     *
     * @param Filesystem $filesystem
     * @param Filter $filter
     * @param City $cityResource
     * @param RegionFactory $regionFactory
     * @param integer $pageSize
     */
    public function __construct(
        Filesystem $filesystem,
        Filter $filter,
        City $cityResource,
        RegionFactory $regionFactory,
        $pageSize = 200
    ) {
        $this->filter = $filter;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->pageSize = $pageSize;
        $this->cityResource = $cityResource;
        $this->regionFactory = $regionFactory;
    }

    /**
     * Returns CSV file
     *
     * @return array
     * @throws LocalizedException
     */
    public function getCsvFile()
    {
        $component = $this->filter->getComponent();

        // md5() here is not for cryptographic use.
        // phpcs:ignore Magento2.Security.InsecureFunction
        $fileName = $component->getName()."_".date("d_m_Y_H_i_s").".csv";
        $file = 'export/'. $fileName;

        $this->filter->prepareComponent($component);
        $this->filter->applySelectionOnTargetProvider();
        $dataProvider = $component->getContext()->getDataProvider();
        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();
        $headers = array_keys(
            $this->removeUnnecessaryData(
                $this->cityResource->getConnection()->describeTable($this->cityResource->getMainTable())
            )
        );
        $stream->writeCsv($headers);
        $i = 1;
        $searchCriteria = $dataProvider->getSearchCriteria()
            ->setCurrentPage($i)
            ->setPageSize($this->pageSize);
        $totalCount = (int) $dataProvider->getSearchResult()->getTotalCount();
        while ($totalCount > 0) {
            $items = $dataProvider->getSearchResult()->getItems();
            foreach ($items as $item) {
                $rowData = $this->removeUnnecessaryData($item->getData());
                $stream->writeCsv($rowData);
            }
            $searchCriteria->setCurrentPage(++$i);
            $totalCount = $totalCount - $this->pageSize;
        }
        $stream->unlock();
        $stream->close();

        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }

    /**
     * Remove unnecessary data.
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    protected function removeUnnecessaryData(array $data)
    {
        if (!is_array($data)) {
            throw new LocalizedException(__("wrong data type, expected type of array"));
        }
        unset($data['id_field_name']);
        unset($data['orig_data']);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['created_at']);
        unset($data['updated_at']);
        
        return $data;
    }
}
