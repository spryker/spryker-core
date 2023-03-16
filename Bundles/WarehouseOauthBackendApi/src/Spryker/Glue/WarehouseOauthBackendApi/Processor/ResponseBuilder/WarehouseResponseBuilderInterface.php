<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

interface WarehouseResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createForbiddenErrorResponse(): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OauthErrorTransfer $oauthErrorTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createOauthBadRequestErrorResponse(OauthErrorTransfer $oauthErrorTransfer): GlueResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseTokenResponse(OauthResponseTransfer $oauthResponseTransfer): GlueResponseTransfer;
}
