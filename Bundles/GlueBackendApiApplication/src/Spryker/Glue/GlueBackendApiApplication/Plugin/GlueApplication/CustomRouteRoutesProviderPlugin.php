<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class CustomRouteRoutesProviderPlugin extends AbstractPlugin implements RoutesProviderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getApplicationName(): string
    {
        return static::GLUE_BACKEND_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Returns the stack of `\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface` for the current application.
     *
     * @api
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    public function getRouteProviderPlugins(): array
    {
        return $this->getFactory()->getRouteProviderPlugins();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<mixed>
     */
    public function getConfiguration(): array
    {
        return [
            'options' => $this->getConfig()->getRouterConfiguration(),
        ];
    }
}
