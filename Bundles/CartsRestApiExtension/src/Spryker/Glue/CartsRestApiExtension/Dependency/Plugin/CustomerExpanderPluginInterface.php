<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CustomerExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands customer transfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expand(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer;
}
