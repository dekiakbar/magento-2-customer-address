<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\Adminhtml\City;

class Delete extends \Deki\CustomerAddress\Controller\Adminhtml\City
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('city_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Deki\CustomerAddress\Model\City::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the City.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['city_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a City to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
