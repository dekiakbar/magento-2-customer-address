<?php
/**
 * Copyright © Deki All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Deki\CustomerAddress\Controller\City;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InvalidArgumentException;

class Search implements HttpGetActionInterface
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $http;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Deki\CustomerAddress\Model\ResourceModel\City\CollectionFactory
     */
    protected $cityCollectionFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \Magento\Framework\App\Response\Http $http
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Deki\CustomerAddress\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
     */
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\App\Response\Http $http,
        \Magento\Framework\App\RequestInterface $request,
        \Deki\CustomerAddress\Model\ResourceModel\City\CollectionFactory $cityCollectionFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->serializer = $json;
        $this->http = $http;
        $this->request = $request;
        $this->cityCollectionFactory = $cityCollectionFactory;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        try {
            $query = $this->request->getParam("query");
            $regionId = $this->request->getParam("region_id");
            return $this->jsonResponse(
                $this->cityCollectionFactory->create()->searchByQueryRegionId($query, $regionId)->getData()
            );
        } catch (InvalidArgumentException $e) {
            return $this->jsonResponse(["error" => true, "message" => $e->getMessage()]);
        } catch (\Exception $e) {
            return $this->jsonResponse(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * Create json response
     *
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        $this->http->setHeader('Content-Type', 'application/json');
        return $this->http->setBody(
            $this->serializer->serialize($response)
        );
    }
}
