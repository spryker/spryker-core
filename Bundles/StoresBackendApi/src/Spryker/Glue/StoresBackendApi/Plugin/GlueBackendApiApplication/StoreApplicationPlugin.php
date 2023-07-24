<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresBackendApi\Plugin\GlueBackendApiApplication;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\StoresBackendApi\StoresBackendApiFactory getFactory()
 */
class StoreApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_STORE_NAME = 'Store';

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAME = '_store';

    /**
     * {@inheritDoc}
     * - Gets store name from the Request header or from the Request parameter.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $storeName = $this->getStoreName();

        if ($storeName === '') {
            return $container;
        }

        $container->set(static::SERVICE_STORE, function (ContainerInterface $container) use ($storeName) {
            return $storeName;
        });

        return $container;
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        $request = $this->getFactory()->createRequest();

        if ($request->query->get(static::PARAMETER_STORE_NAME)) {
            return (string)$request->query->get(static::PARAMETER_STORE_NAME);
        }

        return (string)$request->headers->get(static::HEADER_STORE_NAME);
    }
}
