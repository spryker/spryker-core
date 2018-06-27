<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProductExtension;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionTypeStrategyPluginInterface;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 */
class SetMerchantRelationshipPriceProductDimensionTypeStrategyPlugin extends AbstractPlugin implements PriceProductDimensionTypeStrategyPluginInterface
{
    /**
     * Specification:
     *  - Returns true if strategy can be used for the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return bool
     */
    public function isApplicable(PriceProductDimensionTransfer $priceProductDimensionTransfer): bool
    {
        return $priceProductDimensionTransfer->getIdMerchantRelationship() !== null;
    }

    /**
     * Specification:
     *  - Returns strategy type string
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return PriceProductMerchantRelationshipConstants::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }
}
