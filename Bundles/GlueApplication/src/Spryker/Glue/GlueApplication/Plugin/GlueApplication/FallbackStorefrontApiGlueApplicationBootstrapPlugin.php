<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class FallbackStorefrontApiGlueApplicationBootstrapPlugin extends AbstractPlugin implements GlueApplicationBootstrapPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return bool
     */
    public function isServing(GlueApiContextTransfer $glueApiContextTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function getApplication(): ApplicationInterface
    {
        return $this->getFactory()->createGlueStorefrontFallbackApiApplication();
    }
}
