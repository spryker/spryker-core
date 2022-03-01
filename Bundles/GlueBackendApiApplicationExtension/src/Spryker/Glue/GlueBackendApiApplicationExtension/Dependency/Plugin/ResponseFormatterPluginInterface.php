<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

/**
 * Use this plugin for implementing response formatting in GlueBackendApiApplication.
 */
interface ResponseFormatterPluginInterface
{
    /**
     * Specification:
     * - Formats the `GlueResponseTransfer` for GlueBackendApiApplication.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function format(GlueResponseTransfer $glueResponseTransfer, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
