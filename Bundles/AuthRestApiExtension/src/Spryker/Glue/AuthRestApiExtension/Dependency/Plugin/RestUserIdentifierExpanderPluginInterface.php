<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestUserIdentifierTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface RestUserIdentifierExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands rest user identifier with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestUserIdentifierTransfer $restUserIdentifierTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserIdentifierTransfer
     */
    public function expand(
        RestUserIdentifierTransfer $restUserIdentifierTransfer,
        RestRequestInterface $restRequest
    ): RestUserIdentifierTransfer;
}
