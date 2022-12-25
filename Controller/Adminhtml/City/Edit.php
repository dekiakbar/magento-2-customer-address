<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\Adminhtml\City;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Deki\CustomerAddress\Api\CityRepositoryInterface;

class Edit extends \Deki\CustomerAddress\Controller\Adminhtml\City
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CityRepositoryInterface
     */
    protected $cityRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        CityRepositoryInterface $cityRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->cityRepository = $cityRepository;
        parent::__construct(
            $context,
            $coreRegistry
        );
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('city_id');
        // 2. Initial checking
        if ($id) {
            $city = $this->cityRepository->get($id);
            if (!$city->getCityId()) {
                $this->messageManager->addErrorMessage(__('This City no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('deki_customeraddress_city', $city);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit City') : __('New City'),
            $id ? __('Edit City') : __('New City')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Citys'));
        $resultPage->getConfig()->getTitle()->prepend($city->getCityId() ?
            __('Edit City (ID: %1)', $city->getCityId()) : __('New City'));
        return $resultPage;
    }
}
