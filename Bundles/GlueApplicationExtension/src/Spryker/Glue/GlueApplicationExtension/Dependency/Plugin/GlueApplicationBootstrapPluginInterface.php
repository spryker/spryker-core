<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Shared\Application\ApplicationInterface;

interface GlueApplicationBootstrapPluginInterface
{
    /**
     * Specification:
     * - Indicates if the given context can be handled by this Glue API application bootstrap.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return bool
     */
    public function isServing(GlueApiContextTransfer $glueApiContextTransfer): bool;

    /**
     * Specification:
     * - Returns the Application class responsible for executing the application.
     *
     * @api
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function getApplication(): ApplicationInterface;
}
