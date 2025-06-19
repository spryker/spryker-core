<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Activate;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;

interface MultiFactorAuthTypeActivateProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function activateMultiFactorAuth(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): GlueResponseTransfer;
}
