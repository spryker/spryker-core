<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class SecurityHeaderResponseFormatterPlugin extends AbstractPlugin implements ResponseFormatterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds security headers to the GlueResponseTransfer for GlueBackendApiApplication.
     *
     * @api
     *
     * @see {@link \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig::getSecurityHeaders()}
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer->setMeta(array_merge($glueResponseTransfer->getMeta(), $this->getConfig()->getSecurityHeaders()));

        return $glueResponseTransfer;
    }
}
