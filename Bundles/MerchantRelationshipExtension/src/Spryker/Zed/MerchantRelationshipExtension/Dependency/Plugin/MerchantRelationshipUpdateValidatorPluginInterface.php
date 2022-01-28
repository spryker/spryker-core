<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;

/**
 * Implement this plugin to add validation to `MerchantRelationshipTransfer` before update action.
 */
interface MerchantRelationshipUpdateValidatorPluginInterface
{
    /**
     * Specification:
     * - Validates `MerchantRelationshipTransfer` before update action will be executed.
     * - Adds validation errors to `MerchantRelationshipValidationErrorCollectionTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer
     */
    public function validate(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipValidationErrorCollectionTransfer $merchantRelationshipValidationErrorCollectionTransfer
    ): MerchantRelationshipValidationErrorCollectionTransfer;
}
