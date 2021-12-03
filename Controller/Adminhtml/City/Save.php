<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\Adminhtml\City;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    protected $commonHelper;
    protected $csv;
    protected $regionModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Deki\CustomerAddress\Helper\Common $commonHelper,
        \Deki\CustomerAddress\Helper\Csv $csv,
        \Magento\Directory\Model\Region $regionModel
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->commonHelper = $commonHelper;
        $this->csv = $csv;
        $this->regionModel = $regionModel;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('city_id');
        
            $model = $this->_objectManager->create(\Deki\CustomerAddress\Model\City::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This City no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
            
            $regionId = $this->getRequest()->getParam('region_id');
            $region = $this->getRequest()->getParam('region');
            if (isset($region) && empty($regionId)) {
                $regionCode = $this->commonHelper->buildRegionCodeFromName(
                    $this->getRequest()->getParam('country_id'),
                    $region
                );
                $countryId = $this->getRequest()->getParam('country_id');
                
                if ($this->commonHelper->isRegionExistByCode($countryId, $regionCode)) {
                    $regionId = $this->regionModel->loadByCode($regionCode, $countryId)->getId();
                    $model->setRegionId($regionId);
                } else {
                    $newRegionId = $this->commonHelper->saveNewRegion($countryId, $regionCode, $region);
                    $model->setRegionId($newRegionId);
                }
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the City.'));
                $this->dataPersistor->clear('deki_customeraddress_city');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['city_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the City.'));
            }
        
            $this->dataPersistor->set('deki_customeraddress_city', $data);
            return $resultRedirect->setPath('*/*/edit', ['city_id' => $this->getRequest()->getParam('city_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
