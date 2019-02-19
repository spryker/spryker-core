<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthCompanyUser\Processor\RestUser;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface RestUserExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    public function expand(RestUserTransfer $restUserTransfer, RestRequestInterface $restRequest): RestUserTransfer;
}
