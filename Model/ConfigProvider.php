<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Deki\CustomerAddress\Helper\Config
     */
    protected $configHelper;

    /**
     * @param \Deki\CustomerAddress\Helper\Config $configHelper
     */
    public function __construct(
        \Deki\CustomerAddress\Helper\Config $configHelper
    ) {
        $this->configHelper = $configHelper;
    }

    /**
     * Pass system config to checkout
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $config['customerAddress']['postcodeEnabled'] = $this->configHelper->isPostcodeEnabled();
        $config['customerAddress']['minimunSearcLength'] = $this->configHelper->getMinimunSearcLength();

        return $config;
    }
}
