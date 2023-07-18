<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerConfigurationCacheCollectorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class ControllerConfigurationCacheCollectorPlugin extends AbstractPlugin implements ControllerConfigurationCacheCollectorPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Returns controllers configuration for GlueBackendApiApplication.
     *
     * @api
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function getControllerConfiguration(): array
    {
        return $this->getFactory()->createControllerCacheCollector()->collect();
    }

    /**
     * {@inheritDoc}
     *  - Checks whether the requested application context equals to GlueBackendApiApplication.
     *
     * @api
     *
     * @param string $apiApplication
     *
     * @return bool
     */
    public function isApplicable(string $apiApplication): bool
    {
        return $apiApplication === static::GLUE_BACKEND_API_APPLICATION;
    }
}
