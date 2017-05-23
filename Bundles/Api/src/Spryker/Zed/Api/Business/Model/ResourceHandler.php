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
     * @param \Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface[] $pluginCollection
     */
    public function __construct(array $pluginCollection)
    {
        $this->pluginCollection = $pluginCollection;
    }

    /**
     * @param string $resource
     * @param string $method
     * @param mixed $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return mixed
     */
    public function execute($resource, $method, $params)
    {
        foreach ($this->pluginCollection as $plugin) {
            if (mb_strtolower($plugin->getResourceName()) === mb_strtolower($resource)) {
                if ($method === ApiConfig::ACTION_OPTIONS) {
                    return $this->options($plugin, $params);
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
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer
     */
    protected function options(ApiResourcePluginInterface $plugin, array $params)
    {
        $options = [
            'options',
            'get',
            'post',
            'patch',
            'delete',
        ];
        if (method_exists($plugin, 'getOptions')) {
            $options = $plugin->getOptions();
        }

        $apiCollectionTransfer = new ApiOptionsTransfer();
        $apiCollectionTransfer->setOptions($options);

        return $apiCollectionTransfer;
    }

}
