<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;

class PriceProductMerchantRelationshipStorageToPriceProductFacadeBridge implements PriceProductMerchantRelationshipStorageToPriceProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return array
     */
    public function findPricesBySkuGroupedForCurrentStore(
        $sku,
        ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null
    ): array {
        return $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore($sku, $priceProductDimensionTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return array
     */
    public function findPricesWithTiersBySkuGrouped(
        string $sku,
        ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null
    ): array {
        return $this->priceProductFacade->findPricesWithTiersBySkuGrouped($sku, $priceProductDimensionTransfer);
    }
}
