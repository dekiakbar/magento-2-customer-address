<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Model;

use Magento\Framework\Module\PackageInfo;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Exception;

class Info
{
    /**
     * Github url config path
     *
     * @var string
     */
    public const GITHUB_URL = "customeraddress/info/github_url";

    /**
     * Documentation url config path
     *
     * @var string
     */
    public const DOCS_URL = "customeraddress/info/docs_url";

    /**
     * Package info class
     *
     * @var PackageInfo
     */
    protected $packageInfo;

    /**
     * Module name var
     *
     * @var string
     */
    protected $moduleName;

    /**
     * ScopeConfig class.
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @param PackageInfo $packageInfo
     * @param ScopeConfigInterface $scopeConfig
     * @param ClientInterface $client
     * @param Json $json
     * @param LoggerInterface $logger
     */
    public function __construct(
        PackageInfo $packageInfo,
        ScopeConfigInterface $scopeConfig,
        ClientInterface $client,
        Json $json,
        LoggerInterface $logger
    ) {
        $this->packageInfo = $packageInfo;
        $this->scopeConfig = $scopeConfig;
        $this->client = $client;
        $this->json = $json;
        $this->logger = $logger;
    }
    
    /**
     * Retrieve module name
     *
     * @return string
     */
    public function getModuleName()
    {
        if (!$this->moduleName) {
            $class = get_class($this);
            $this->moduleName = substr($class, 0, strpos($class, '\\Model'));
        }
        return str_replace('\\', '_', $this->moduleName);
    }

    /**
     * Get package name of a module
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageInfo->getPackageName($this->getModuleName());
    }

    /**
     * Get package version of a module
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->packageInfo->getVersion($this->getModuleName());
    }

    /**
     * Get Github url
     *
     * @return string
     */
    public function getGithubUrl()
    {
        return $this->scopeConfig->getValue(self::GITHUB_URL, 'default');
    }

    /**
     * Get Documentation url
     *
     * @return string
     */
    public function getDocsUrl()
    {
        return $this->scopeConfig->getValue(self::DOCS_URL, 'default');
    }

    /**
     * Get latest available version of module
     *
     * @return string
     */
    public function getLatestVersion()
    {
        try {
            $this->client->get('https://packagist.org/packages/'.$this->getPackageName().'.json');
            if ($this->client->getStatus() !== 200) {
                throw new LocalizedException(__('Unable to connect packagist API'));
            }
    
            $res = $this->json->unserialize($this->client->getBody());
            unset($res['package']['versions']['dev-master']);
            $latest = array_key_first($res['package']['versions']);
            
            return $latest;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return __("Ops...cannot check latest version, please check the log at var/log/customer-address");
        }
    }
}
