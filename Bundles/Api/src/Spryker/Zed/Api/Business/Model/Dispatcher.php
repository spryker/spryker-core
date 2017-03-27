<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;

class Dispatcher
{

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $preProcessStack;

    /**
     * @var array
     */
    protected $postProcessStack;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $config
     * @param \Spryker\Zed\Api\Communication\Plugin\PreProcess\PreProcessPluginInterface[] $preProcessStack
     * @param \Spryker\Zed\Api\Communication\Plugin\PostProcess\PostProcessPluginInterface[] $postProcessStack
     */
    public function __construct(ApiConfig $config, array $preProcessStack, array $postProcessStack)
    {
        $this->config = $config;
        $this->preProcessStack = $preProcessStack;
        $this->postProcessStack = $postProcessStack;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function dispatch(ApiRequestTransfer $apiRequestTransfer)
    {
        $this->preProcess($apiRequestTransfer);

        $resource = $apiRequestTransfer->getResource();
        $method = $apiRequestTransfer->getResourceAction();
        $params = $apiRequestTransfer->getResourceParams();

        // right now can also be transfer
        $entityOrCollection = $this->callApiPlugin($resource, $method, $params);

        $apiResponseTransfer = new ApiResponseTransfer();
        $apiResponseTransfer->setData($entityOrCollection->toArray());

        $this->postProcess($apiResponseTransfer);

        return $apiResponseTransfer;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param array $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return mixed
     */
    protected function callApiPlugin($resource, $method, $params)
    {
        $pluginClass = $this->config->getPluginForResource($resource);

        if (!method_exists($pluginClass, $method)) {
            throw new ApiDispatchingException(sprintf('Method %s() not found on Plugin class %s', $method, $pluginClass));
        }

        $plugin = new $pluginClass($this->config);

        return call_user_func_array([$plugin, $method], $params);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    protected function preProcess(ApiRequestTransfer $apiRequestTransfer)
    {
        foreach ($this->preProcessStack as $plugin) {
            if (is_string($plugin)) {
                $plugin = new $plugin();
            }
            $plugin->process($apiRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return void
     */
    protected function postProcess(ApiResponseTransfer $apiResponseTransfer)
    {
        foreach ($this->postProcessStack as $plugin) {
            if (is_string($plugin)) {
                $plugin = new $plugin();
            }
            $plugin->process($apiResponseTransfer);
        }
    }

}
