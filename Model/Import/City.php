<?php
namespace Deki\CustomerAddress\Model\Import;

use Deki\CustomerAddress\Model\Import\Validator\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

class City extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const ID = 'city_id';
    const COUNTRY_ID = 'country_id';
    const REGION_ID = 'region_id';
    const NAME = 'name';
    const POSTCODE = 'postcode';
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
    const TABLE_ENTITY= 'deki_customeraddress_city';
    
    /**
     * Validation failure city template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_MESSAGE_IS_EMPTY => 'Message is empty',
    ];

    /**
     *
     * @var array
     */
    protected $_permanentAttributes = [self::ID];
    
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;

    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::ID,
        self::COUNTRY_ID,
        self::REGION_ID,
        self::NAME,
        self::POSTCODE,
        self::UPDATED_AT,
        self::CREATED_AT
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
    }

    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'deki_customeraddress';
    }

    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        $title = false;
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * Create Advanced city data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        $this->saveEntity();
        return true;
    }

    /**
     * Save city
     *
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    /**
     * Save and replace data city
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
                $rowTtile= $rowData[self::ID];
                $listTitle[] = $rowTtile;
                $entityList[$rowTtile][] = [
                    self::ID => $rowData[self::ID],
                    self::COUNTRY_ID => $rowData[self::COUNTRY_ID],
                    self::REGION_ID => $rowData[self::REGION_ID],
                    self::NAME => $rowData[self::NAME],
                    self::POSTCODE => $rowData[self::POSTCODE],
                    self::UPDATED_AT => $rowData[self::UPDATED_AT],
                    self::CREATED_AT => $rowData[self::CREATED_AT]
                ];
            }
            
            $rewCreated = [];
            $rowUpdated = [];
            $rewDeleted = [];
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listTitle) {
                    if ($this->deleteEntityDb(array_unique($listTitle), self::TABLE_ENTITY)) {
                        $this->saveEntityDb($entityList, self::TABLE_ENTITY);
                        $rowUpdated = $entityList;
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityDb($entityList, self::TABLE_ENTITY);
                $rewCreated = $entityList;
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $behavior) {
                $this->deleteEntityDb(array_unique($listTitle), self::TABLE_ENTITY);
                $rewDeleted = $listTitle;
            }
        }

        $this->updateItemsCounterStats($rewCreated, $rowUpdated, $rewDeleted);
        return $this;
    }

    /**
     * Save city to customtable.
     *
     * @param array $entityData
     * @param string $table
     * @return $this
     */
    protected function saveEntityDb(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                foreach ($entityRows as $row) {
                    $entityIn[] = $row;
                }
            }
            if ($entityIn) {
                $this->_connection->insertOnDuplicate(
                    $tableName,
                    $entityIn,
                    [
                        self::ID,
                        self::COUNTRY_ID,
                        self::REGION_ID,
                        self::NAME,
                        self::POSTCODE,
                        self::UPDATED_AT,
                        self::CREATED_AT
                    ]
                );
            }
        }
        return $this;
    }

    /**
     * Save city to customtable.
     *
     * @param array $entityData
     * @param string $table
     * @return $this
     */
    public function deleteEntityDb(array $listTitle, $table)
    {
        if ($listTitle) {
            $tableName = $this->_connection->getTableName($table);
            $this->_connection->delete(
                $tableName,
                self::ID." IN (".implode(",", $listTitle).")"
            );
        }
        return $this;
    }

    /**
     * Update proceed items counter
     *
     * @param array $created
     * @param array $updated
     * @param array $deleted
     * @return $this
     */
    protected function updateItemsCounterStats(array $created = [], array $updated = [], array $deleted = [])
    {
        $this->countItemsCreated = count($created);
        $this->countItemsUpdated = count($updated);
        $this->countItemsDeleted = count($deleted);
        return $this;
    }
}
