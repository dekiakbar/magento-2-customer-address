<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Block\Adminhtml\Customer\Address\Edit;

use \Magento\Framework\Serialize\SerializerInterface;
use \Magento\Framework\View\Element\Template\Context;
use Deki\CustomerAddress\Helper\Config;
use \Magento\Framework\Url;

class ConfigJs extends \Magento\Framework\View\Element\Template
{
    public const REST_API_PATH_URL = 'rest/default/V1/customer-address/autocomplete';
    /**
     * @var Url
     */
    protected $urlBuider;

    /**
     * @var SerializerInterface
     */
    protected $serializerInterface;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Context $context
     * @param SerializerInterface $serializerInterface
     * @param Url $urlBuilder
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializerInterface,
        Url $urlBuilder,
        Config $config,
        array $data = []
    ) {
        $this->serializerInterface = $serializerInterface;
        $this->urlBuilder = $urlBuilder;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * Add system config value
     *
     * @return array
     */
    public function getSerializedConfig()
    {
        $config = [
            'postcodeEnabled' => $this->config->isPostcodeEnabled() ? true : false,
            'minSearchLength' => $this->config->getMinimunSearcLength(),
            'customerAddressUrl' => $this->getAdminAjaxUrl()
        ];

        return $this->serializerInterface->serialize($config);
    }

    /**
     * Get ajax url
     *
     * @return void
     */
    public function getAdminAjaxUrl()
    {
        return $this->urlBuilder->getUrl(
            self::REST_API_PATH_URL
        );
    }
}
