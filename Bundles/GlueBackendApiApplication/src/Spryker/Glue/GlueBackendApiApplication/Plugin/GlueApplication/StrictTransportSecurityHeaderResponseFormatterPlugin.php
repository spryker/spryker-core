<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class StrictTransportSecurityHeaderResponseFormatterPlugin extends AbstractPlugin implements ResponseFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_STRICT_TRANSPORT_SECURITY = 'Strict-Transport-Security';

    /**
     * {@inheritDoc}
     * - Adds strict security header to the GlueResponseTransfer for GlueBackendApiApplication.
     *
     * @api
     *
     * @see {@link \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig::getStrictTransportSecurityHeader()}
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $meta = $glueResponseTransfer->getMeta();
        $meta[static::HEADER_STRICT_TRANSPORT_SECURITY] = $this->getConfig()->getStrictTransportSecurityHeader();

        $glueResponseTransfer->setMeta($meta);

        return $glueResponseTransfer;
    }
}
