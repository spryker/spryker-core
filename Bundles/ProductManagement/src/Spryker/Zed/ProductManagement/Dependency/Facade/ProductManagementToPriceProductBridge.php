<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

class ProductManagementToPriceProductBridge implements ProductManagementToPriceProductInterface
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
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null): ?int
    {
        return $this->priceProductFacade->findPriceBySku($sku, $priceTypeName);
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceTypeTransfer>
     */
    public function getPriceTypeValues(): array
    {
        return $this->priceProductFacade->getPriceTypeValues();
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName(): string
    {
        return $this->priceProductFacade->getDefaultPriceTypeName();
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePrices(int $idProductConcrete, int $idProductAbstract): array
    {
        return $this->priceProductFacade->findProductConcretePrices($idProductConcrete, $idProductAbstract);
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType(): string
    {
        return $this->priceProductFacade->getPriceModeIdentifierForBothType();
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtraction(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtraction(
            $idProductAbstract,
            $priceProductCriteriaTransfer,
        );
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePricesWithoutPriceExtraction(
        int $idProductConcrete,
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->priceProductFacade->findProductConcretePricesWithoutPriceExtraction(
            $idProductConcrete,
            $idProductAbstract,
            $priceProductCriteriaTransfer,
        );
    }
}
