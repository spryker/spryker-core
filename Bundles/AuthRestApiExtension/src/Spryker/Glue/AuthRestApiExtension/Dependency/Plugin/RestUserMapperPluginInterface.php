<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface RestUserMapperPluginInterface
{
    /**
     * Specification:
     * - Maps additional data to the rest user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function map(
        RestUserTransfer $restUserTransfer,
        RestRequestInterface $restRequest
    ): RestUserTransfer;
}
