<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class BackendApiGlueApplicationBootstrapPlugin extends AbstractPlugin implements GlueApplicationBootstrapPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks current application is GLUE_BACKEND.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return bool
     */
    public function isServing(GlueApiContextTransfer $glueApiContextTransfer): bool
    {
        return APPLICATION === 'GLUE_BACKEND';
    }

    /**
     * {@inheritDoc}
     * - Return the {@link \Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication}.
     *
     * @api
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function getApplication(): ApplicationInterface
    {
        return $this->getFactory()->createGlueBackendApiApplication();
    }
}
