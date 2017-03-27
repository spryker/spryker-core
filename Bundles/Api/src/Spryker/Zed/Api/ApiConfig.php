<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api;

use Spryker\Zed\Api\Business\Exception\PluginNotFoundException;
use Spryker\Zed\Api\Business\Model\Processor\Pre\ConditionsByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FieldsByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FindActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FormatTypeByExtensionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\FormatTypeByHeaderPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationByHeaderFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\ResourceActionPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\ResourceParamsPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\ResourcePreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\SortByQueryFilterPreProcessor;
use Spryker\Zed\Api\Business\Model\Processor\Pre\UpdateActionPreProcessor;
use Spryker\Zed\CustomerApi\Communication\Plugin\Api\CustomerApiPlugin;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiConfig extends AbstractBundleConfig
{

    const ROUTE_PREFIX_API_REST = '/api/rest/';

    const FORMAT_TYPE = 'json';

    /**
     * @param string $resource
     *
     * @throws \Spryker\Zed\Api\Business\Exception\PluginNotFoundException
     *
     * @return string
     */
    public function getPluginForResource($resource)
    {
        $map = $this->getResourceToPluginMap();
        if (!isset($map[$resource])) {
            throw new PluginNotFoundException('No plugin found for resource %s');
        }

        return $map[$resource];
    }

    /**
     * @return array
     */
    protected function getResourceToPluginMap()
    {
        return [
            'customers' => CustomerApiPlugin::class, // Sales /api/orders => findOrders()
            'customer-addresses' => CustomerAddressApiPlugin::class, // Sales /api/orders => findOrders()
            'orders' => OrderApiPlugin::class, // /SalesApi/Plugin/Api/OrderApiPlugin Sales /api/orders/1 => getOrder(1)
        ];
    }

    /**
     * Stack of plugins to be used for filtering, pagination and alike in use with find() index method.
     *
     * @return array
     */
    public function getPreProcessorStack()
    {
        return [
            PathPreProcessor::class,
            FormatTypeByHeaderPreProcessor::class,
            FormatTypeByExtensionPreProcessor::class,
            ResourcePreProcessor::class,
            ResourceActionPreProcessor::class,
            ResourceParamsPreProcessor::class,
            FindActionPreProcessor::class,
            UpdateActionPreProcessor::class,
            FilterPreProcessor::class,
            FieldsByQueryFilterPreProcessor::class,
            SortByQueryFilterPreProcessor::class,
            ConditionsByQueryFilterPreProcessor::class,
            PaginationByQueryFilterPreProcessor::class,
            PaginationByHeaderFilterPreProcessor::class,
        ];
    }

    /**
     * Stack of plugins to be used for output processing of the final transfer with find() index method.
     *
     * @return array
     */
    public function getPostProcessorStack()
    {
        return [
            //CustomHeader
            //'DateTimeFormat'
        ];
    }

    /**
     * @param string $resource
     *
     * @return array
     */
    public function getFields($resource)
    {
        $map = $this->getFieldMap();

        return $map[$resource];
    }

    /**
     * Whitelist of fields per resource.
     *
     * @return array
     */
    protected function getFieldMap()
    {
        return [
            'users' => [],
            'customers' => ['id', 'name'], // no password
        ];
    }

}
