<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListConfig getConfig()
 */
class ProductListMerchantRelationshipUpdateValidatorPlugin extends AbstractPlugin implements MerchantRelationshipUpdateValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates if passed `MerchantRelationshipTransfer.productListIds` are available for merchant relationship.
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
    ): MerchantRelationshipValidationErrorCollectionTransfer {
        return $this->getFacade()->validateMerchantRelationshipProductList(
            $merchantRelationshipTransfer,
            $merchantRelationshipValidationErrorCollectionTransfer,
        );
    }
}
