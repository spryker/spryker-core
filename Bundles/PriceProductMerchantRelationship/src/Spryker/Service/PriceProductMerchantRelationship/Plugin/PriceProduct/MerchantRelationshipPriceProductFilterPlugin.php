<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig;

/**
 * @method \Spryker\Service\PriceProductMerchantRelationship\PriceProductMerchantRelationshipServiceInterface getService()
 */
class MerchantRelationshipPriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters `PriceProductTransfers` by `PriceProductFilterTransfer.priceDimension.idMerchantRelationship`.
     * - Filters out all `PriceProductTransfers` with merchant relationship if `PriceProductFilterTransfer.priceDimension.idMerchantRelationship` is not set.
     * - Filters out all `PriceProductTransfers` where `PriceProductTransfer.priceDimension.idMerchantRelationship` is different from `PriceProductFilterTransfer.priceDimension.idMerchantRelationship`.
     * - When `PriceProductFilterTransfer.priceDimension.idMerchantRelationship` is set and `PriceProductTransfer` doesn't have a merchant relationship, it is not filtered out.
     * - Filters `PriceProductTransfers` by `PriceProductFilterTransfer.priceDimension.isMerchantActive` transfer property.
     * - Filters out all `PriceProductTransfers` if `PriceProductFilterTransfer.priceDimension.idMerchantActive` transfer property is false.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getService()->filterPriceProductsByMerchantRelationship(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductMerchantRelationshipConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP;
    }
}
