<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;

class PriceProductReader implements PriceProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface
     */
    protected $priceProductVolumeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface $priceProductVolumeFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductMerchantPortalGuiToPriceProductVolumeFacadeInterface $priceProductVolumeFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->productFacade = $productFacade;
        $this->priceProductVolumeFacade = $priceProductVolumeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getPriceProducts(PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer): array
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdProductConcrete($priceProductTableCriteriaTransfer->getIdProductConcrete())
            ->setIdProductAbstract($priceProductTableCriteriaTransfer->getIdProductAbstract());

        $priceProductTransfers = [];

        if ($priceProductCriteriaTransfer->getIdProductConcrete() !== null) {
            $priceProductCriteriaTransfer->setOnlyConcretePrices(true);
            $priceProductCriteriaTransfer->setPriceDimension(
                (new PriceProductDimensionTransfer())
                ->setType(PriceProductConfig::PRICE_DIMENSION_DEFAULT)
            );

            $idProductConcrete = $priceProductCriteriaTransfer->getIdProductConcreteOrFail();
            $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProductConcrete);

            if ($idProductAbstract !== null) {
                $priceProductTransfers = $this->priceProductFacade->findProductConcretePricesWithoutPriceExtraction(
                    $idProductConcrete,
                    $idProductAbstract,
                    $priceProductCriteriaTransfer
                );
                $priceProductTransfers = array_merge(
                    $priceProductTransfers,
                    $this->priceProductVolumeFacade->extractPriceProductVolumeTransfersFromArray($priceProductTransfers)
                );
            }
        }

        if ($priceProductCriteriaTransfer->getIdProductAbstract() !== null) {
            $priceProductTransfers = $this->priceProductFacade->findProductAbstractPrices(
                $priceProductCriteriaTransfer->getIdProductAbstractOrFail(),
                $priceProductCriteriaTransfer
            );
        }

        return $this->filterProductPriceTransfers($priceProductTransfers, $priceProductTableCriteriaTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterProductPriceTransfers(
        array $priceProductTransfers,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): array {
        foreach ($priceProductTransfers as $index => $priceProductTransfer) {
            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if (
                !$this->isMatchingCurrency($moneyValueTransfer, $priceProductTableCriteriaTransfer)
                || !$this->isMatchingStore($moneyValueTransfer, $priceProductTableCriteriaTransfer)
            ) {
                unset($priceProductTransfers[$index]);
            }
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return bool
     */
    protected function isMatchingCurrency(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): bool {
        if (empty($priceProductTableCriteriaTransfer->getFilterInCurrencies())) {
            return true;
        }

        return in_array(
            $moneyValueTransfer->getFkCurrency(),
            $priceProductTableCriteriaTransfer->getFilterInCurrencies()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return bool
     */
    protected function isMatchingStore(
        MoneyValueTransfer $moneyValueTransfer,
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): bool {
        if (empty($priceProductTableCriteriaTransfer->getFilterInStores())) {
            return true;
        }

        return in_array(
            $moneyValueTransfer->getFkStore(),
            $priceProductTableCriteriaTransfer->getFilterInStores()
        );
    }
}
