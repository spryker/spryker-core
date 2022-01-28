<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Executor;

use Generated\Shared\Transfer\ApiOptionsTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;
use Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface;
use Spryker\Zed\Api\Dependency\Plugin\OptionsForCollectionInterface;
use Spryker\Zed\Api\Dependency\Plugin\OptionsForItemInterface;

class ResourcePluginExecutor implements ResourcePluginExecutorInterface
{
    /**
     * @var array<\Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface>
     */
    protected $apiResourcePlugins;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @param array<\Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface> $apiResourcePlugins
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     */
    public function __construct(array $apiResourcePlugins, ApiConfig $apiConfig)
    {
        $this->apiResourcePlugins = $apiResourcePlugins;
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param int|null $id
     * @param array $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer|\Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function execute($resource, $method, $id, array $params)
    {
        foreach ($this->apiResourcePlugins as $apiResourcePlugin) {
            if (mb_strtolower($apiResourcePlugin->getResourceName()) !== mb_strtolower($resource)) {
                continue;
            }

            if ($method === ApiConfig::ACTION_OPTIONS) {
                return $this->getOptions($apiResourcePlugin, $id, $params);
            }

            /** @var callable $callable */
            $callable = [$apiResourcePlugin, $method];
            if (!is_callable($callable)) {
                throw new ApiDispatchingException($this->createUnsupportedMethodErrorMessage($method, $resource));
            }

            /** @var \Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer $responseTransfer */
            $responseTransfer = call_user_func_array($callable, $params);
            $apiOptionsTransfer = $this->getOptions($apiResourcePlugin, $id, $params);
            $responseTransfer->setOptions($apiOptionsTransfer->getOptions());

            return $responseTransfer;
        }

        throw new ApiDispatchingException($this->createUnsupportedMethodErrorMessage($method, $resource));
    }

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface $plugin
     * @param int|null $resourceId
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer
     */
    protected function getOptions(ApiResourcePluginInterface $plugin, $resourceId, array $params)
    {
        if ($resourceId) {
            $options = $this->getOptionsForItem($plugin, $params);
        } else {
            $options = $this->getOptionsForCollection($plugin, $params);
        }

        $apiOptionsTransfer = new ApiOptionsTransfer();
        $apiOptionsTransfer->setOptions($options);

        return $apiOptionsTransfer;
    }

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface $plugin
     * @param array $params
     *
     * @return array
     */
    protected function getOptionsForItem(ApiResourcePluginInterface $plugin, array $params)
    {
        if ($plugin instanceof OptionsForItemInterface) {
            $options = $plugin->getHttpMethodsForItem($params);
        } else {
            $options = $this->apiConfig->getHttpMethodsForItem();
        }

        if (!in_array(ApiConfig::HTTP_METHOD_OPTIONS, $options)) {
            $options[] = ApiConfig::HTTP_METHOD_OPTIONS;
        }

        return $options;
    }

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface $plugin
     * @param array $params
     *
     * @return array
     */
    protected function getOptionsForCollection(ApiResourcePluginInterface $plugin, array $params)
    {
        if ($plugin instanceof OptionsForCollectionInterface) {
            $options = $plugin->getHttpMethodsForCollection($params);
        } else {
            $options = $this->apiConfig->getHttpMethodsForCollection();
        }

        if (!in_array(ApiConfig::HTTP_METHOD_OPTIONS, $options)) {
            $options[] = ApiConfig::HTTP_METHOD_OPTIONS;
        }

        return $options;
    }

    /**
     * @param string $method
     * @param string $resource
     *
     * @return string
     */
    protected function createUnsupportedMethodErrorMessage(string $method, string $resource): string
    {
        return sprintf('Unsupported method "%s" for resource "%s"', $method, $resource);
    }
}
