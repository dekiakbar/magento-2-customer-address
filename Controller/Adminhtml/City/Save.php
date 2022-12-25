<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\Adminhtml\City;

use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Deki\CustomerAddress\Helper\Common;
use Magento\Directory\Model\Region;
use \Magento\Framework\Controller\ResultInterface;
use Deki\CustomerAddress\Model\CityFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var Common
     */
    protected $commonHelper;

    /**
     * @var Region
     */
    protected $regionModel;

    /**
     * @var CityFactory
     */
    protected $cityFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param Common $commonHelper
     * @param Region $regionModel
     * @param CityFactory $cityFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        Common $commonHelper,
        Region $regionModel,
        CityFactory $cityFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->commonHelper = $commonHelper;
        $this->regionModel = $regionModel;
        $this->cityFactory = $cityFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('city_id');
        
            $model = $this->cityFactory->create()->load($id);
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
