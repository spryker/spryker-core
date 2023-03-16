<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;

class WarehouseUserRequestValidator implements WarehouseUserRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function preCheck(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueRequestValidationTransfer {
        if ($glueRequestTransfer->getRequestWarehouse()) {
            return $glueRequestValidationTransfer->setIsValid(true);
        }

        return $glueRequestValidationTransfer;
    }
}
