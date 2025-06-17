<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

class SspAssetValidator implements SspAssetValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateAsset(SspAssetTransfer $sspAssetTransfer): ArrayObject
    {
        $validationErrors = new ArrayObject();

        if (!$sspAssetTransfer->getName()) {
            $validationErrors->append((new ErrorTransfer())->setMessage('self_service_portal.asset.validation.name.not_set'));
        }

        return $validationErrors;
    }
}
