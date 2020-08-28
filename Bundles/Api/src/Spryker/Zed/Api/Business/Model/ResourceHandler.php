<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model;

use Generated\Shared\Transfer\ApiOptionsTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;
use Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface;
use Spryker\Zed\Api\Dependency\Plugin\OptionsForCollectionInterface;
use Spryker\Zed\Api\Dependency\Plugin\OptionsForItemInterface;

class ResourceHandler implements ResourceHandlerInterface
{
    /**
     * @var \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[]
     */
    protected $pluginCollection;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[] $pluginCollection
     * @param \Spryker\Zed\Api\ApiConfig $config
     */
    public function __construct(array $pluginCollection, ApiConfig $config)
    {
        $this->pluginCollection = $pluginCollection;
        $this->config = $config;
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
        foreach ($this->pluginCollection as $plugin) {
            if (mb_strtolower($plugin->getResourceName()) !== mb_strtolower($resource)) {
                continue;
            }

            if ($method === ApiConfig::ACTION_OPTIONS) {
                return $this->getOptions($plugin, $id, $params);
            }

            /** @var \Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer $responseTransfer */
            $responseTransfer = call_user_func_array([$plugin, $method], $params);
            $apiOptionsTransfer = $this->getOptions($plugin, $id, $params);
            $responseTransfer->setOptions($apiOptionsTransfer->getOptions());

            return $responseTransfer;
        }

        throw new ApiDispatchingException(sprintf(
            'Unsupported method "%s" for resource "%s"',
            $method,
            $resource
        ));
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
            $options = $this->config->getHttpMethodsForItem();
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
            $options = $this->config->getHttpMethodsForCollection();
        }

        if (!in_array(ApiConfig::HTTP_METHOD_OPTIONS, $options)) {
            $options[] = ApiConfig::HTTP_METHOD_OPTIONS;
        }

        return $options;
    }
}
