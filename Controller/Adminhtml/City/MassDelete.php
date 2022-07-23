<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\Adminhtml\City;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Deki\CustomerAddress\Controller\Adminhtml\City;
use Deki\CustomerAddress\Model\ResourceModel\City\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;
use Deki\CustomerAddress\Api\CityRepositoryInterface;
use Magento\Framework\Registry;

/**
 * Class \Deki\CustomerAddress\Controller\Adminhtml\City\MassDelete
 */
class MassDelete extends City implements HttpPostActionInterface
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepositoryInterface;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CityRepositoryInterface|null $cityRepositoryInterface
     * @param LoggerInterface|null $logger
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CityRepositoryInterface $cityRepositoryInterface = null,
        LoggerInterface $logger = null,
        Registry $coreRegistry
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->cityRepositoryInterface = $cityRepositoryInterface ?:
            ObjectManager::getInstance()->create(CityRepositoryInterface::class);
        $this->logger = $logger ?:
            ObjectManager::getInstance()->create(LoggerInterface::class);
        parent::__construct(
            $context,
            $coreRegistry
        );
    }

    /**
     * Mass Delete Action
     *
     * @return Redirect
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $cityDeleted = 0;
        $cityDeletedError = 0;
        /** @var \Deki\CustomerAddress\Model\City $city */
        foreach ($collection->getItems() as $city) {
            try {
                $this->cityRepositoryInterface->delete($city);
                $cityDeleted++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $cityDeletedError++;
            }
        }

        if ($cityDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $cityDeleted)
            );
        }

        if ($cityDeletedError) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $cityDeletedError
                )
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
