<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Deki\CustomerAddress\Model\Info as InfoModel;

class Info extends Field
{
    /**
     * Template pth
     *
     * @var string
     */
    protected $_template = 'Deki_CustomerAddress::system/config/info.phtml';

    /**
     * Info model class
     *
     * @var InfoModel
     */
    protected $infoModel;

    /**
     *
     * @param InfoModel $infoModel
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        InfoModel $infoModel,
        Context $context,
        array $data = []
    ) {
        $this->infoModel = $infoModel;
        parent::__construct($context, $data);
    }

    /**
     * Remove element scope and render form element as HTML
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element->setData('scope', null);
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    /**
     * Get package version of a module
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->infoModel->getVersion();
    }

    /**
     * Get Github url
     *
     * @return string
     */
    public function getGithubUrl()
    {
        return $this->infoModel->getGithubUrl();
    }

    /**
     * Get Documentation url
     *
     * @return string
     */
    public function getDocsUrl()
    {
        return $this->infoModel->getDocsUrl();
    }

    /**
     * Get latest available version of module
     *
     * @return string
     */
    public function getLatestVersion()
    {
        return $this->infoModel->getLatestVersion();
    }
}
