<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class ControllerCacheCollectorPlugin extends AbstractPlugin implements ControllerCacheCollectorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns controllers configuration for GlueStorefrontApiApplication.
     *
     * @api
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function getControllerConfiguration(): array
    {
        return $this->getFactory()->createControllerCacheCollector()->collect();
    }
}
