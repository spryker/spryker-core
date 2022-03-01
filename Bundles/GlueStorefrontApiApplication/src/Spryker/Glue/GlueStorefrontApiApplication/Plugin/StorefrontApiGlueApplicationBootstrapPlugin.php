<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class StorefrontApiGlueApplicationBootstrapPlugin extends AbstractPlugin implements GlueApplicationBootstrapPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the host is being served by Storefront API Application
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return bool
     */
    public function isServing(GlueApiContextTransfer $glueApiContextTransfer): bool
    {
        return APPLICATION === 'GLUE_STOREFRONT';
    }

    /**
     * {@inheritDoc}
     * - Return the {@link \Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication}.
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function getApplication(): ApplicationInterface
    {
        return $this->getFactory()->createGlueStorefrontApiApplication();
    }
}
