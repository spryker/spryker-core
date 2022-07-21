<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

class ApplicationIdentifierRequestBuilderPlugin extends AbstractPlugin implements RequestBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Sets `GlueRequestTransfer::$application` to "GLUE_BACKEND_API_APPLICATION".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function build(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        return $glueRequestTransfer->setApplication(static::GLUE_BACKEND_API_APPLICATION);
    }
}
