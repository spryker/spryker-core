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
     * @param mixed $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return mixed
     */
    public function execute($resource, $method, $id, $params)
    {
        foreach ($this->pluginCollection as $plugin) {
            if (mb_strtolower($plugin->getResourceName()) === mb_strtolower($resource)) {
                if ($method === ApiConfig::ACTION_OPTIONS) {
                    return $this->options($plugin, $id, $params);
                }

                return call_user_func_array([$plugin, $method], $params);
            }
        }

        throw new ApiDispatchingException(sprintf(
            'Unsupported method "%s" for resource "%s"',
            $method,
            $resource
        ));
    }

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface $plugin
     * @param array $params
     * @param int|null $resourceId
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer
     */
    protected function options(ApiResourcePluginInterface $plugin, $resourceId, array $params)
    {
        if ($resourceId) {
            $options = $this->getOptionsForItem($plugin, $params);
        } else {
            $options = $this->getOptionsForCollection($plugin, $params);
        }

        $apiCollectionTransfer = new ApiOptionsTransfer();
        $apiCollectionTransfer->setOptions($options);

        return $apiCollectionTransfer;
    }

    /**
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface $plugin
     * @param array $params
     *
     * @return array
     */
    protected function getOptionsForItem(ApiResourcePluginInterface $plugin, array $params)
    {
        if (method_exists($plugin, 'getHttpMethodsForItem')) {
            $options = $plugin->getHttpMethodsForItem($params);
        } else {
            $options = $this->config->getHttpMethodsForItem();
        }

        $options[] = ApiConfig::HTTP_METHOD_OPTIONS;

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
        if (method_exists($plugin, 'getHttpMethodsForCollection')) {
            $options = $plugin->getHttpMethodsForCollection($params);
        } else {
            $options = $this->config->getHttpMethodsForCollection();
        }

        $options[] = ApiConfig::HTTP_METHOD_OPTIONS;

        return $options;
    }

}
