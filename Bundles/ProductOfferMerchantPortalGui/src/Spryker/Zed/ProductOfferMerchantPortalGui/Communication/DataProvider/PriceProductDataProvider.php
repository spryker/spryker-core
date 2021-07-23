<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface;

class PriceProductDataProvider implements PriceProductDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper
     */
    protected $priceProductOfferMapper;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpanderInterface
     */
    protected $priceProductsVolumeDataExpander;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferMapper $priceProductOfferMapper
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Expander\PriceProductsVolumeDataExpanderInterface $priceProductsVolumeDataExpander
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        PriceProductOfferMapper $priceProductOfferMapper,
        PriceProductsVolumeDataExpanderInterface $priceProductsVolumeDataExpander
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->priceProductOfferMapper = $priceProductOfferMapper;
        $this->priceProductsVolumeDataExpander = $priceProductsVolumeDataExpander;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param int[] $typePriceProductOfferIds
     * @param mixed[] $requestData
     * @param int $volumeQuantity
     * @param int $idProductOffer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPriceProductOfferPrices(
        array $typePriceProductOfferIds,
        array $requestData,
        int $volumeQuantity,
        int $idProductOffer
    ): ArrayObject {
        $priceProductTransfers = $this->getPriceProductTransfers($typePriceProductOfferIds, $requestData);

        if ($volumeQuantity > 1) {
            $priceProductTransfers = $this->priceProductsVolumeDataExpander
                ->expandPriceProductsWithVolumeData($priceProductTransfers, $requestData, $volumeQuantity, $idProductOffer);

            return $priceProductTransfers;
        }

        $priceProductTransfers = $this->priceProductOfferMapper->mapRequestDataToPriceProductTransfers(
            $requestData,
            $priceProductTransfers
        );

        return $priceProductTransfers;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param int[] $typePriceProductOfferIds
     * @param mixed[] $requestData
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(array $typePriceProductOfferIds, array $requestData): ArrayObject
    {
        $key = (string)key($requestData);
        $priceTypeName = mb_strtoupper((string)strstr($key, '[', true));
        $priceProductOfferIds = $this->getPriceProductOfferIds($typePriceProductOfferIds, $key, $priceTypeName);

        if (!$priceProductOfferIds) {
            $priceProductTransfers = new ArrayObject();
            $priceProductTransfer = $this->createNewPriceForProductOffer($typePriceProductOfferIds, $priceTypeName);
            $priceProductTransfers->append($priceProductTransfer);

            return $priceProductTransfers;
        }

        $priceProductOfferCriteriaTransfer = (new PriceProductOfferCriteriaTransfer())
            ->setPriceProductOfferIds($priceProductOfferIds)
            ->setWithExtractedPrices(false);

        $priceProductTransfers = $this->priceProductOfferFacade->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        return $priceProductTransfers;
    }

    /**
     * @param int[] $typePriceProductOfferIds
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewPriceForProductOffer(array $typePriceProductOfferIds, string $priceTypeName): PriceProductTransfer
    {
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($typePriceProductOfferIds);

        $offerPriceProductTransfers = $this->priceProductOfferFacade
            ->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $offerPriceProductTransfer */
        $offerPriceProductTransfer = $offerPriceProductTransfers->getIterator()->current();
        $moneyValueTransfer = $offerPriceProductTransfer->getMoneyValueOrFail();
        $priceProductDimensionTransfer = $offerPriceProductTransfer->getPriceDimensionOrFail();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdProduct($offerPriceProductTransfer->getIdProduct())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setIdProductOffer($priceProductDimensionTransfer->getIdProductOffer())
            )
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setCurrency($moneyValueTransfer->getCurrency())
                    ->setFkStore($moneyValueTransfer->getFkStore())
                    ->setStore($moneyValueTransfer->getStore())
                    ->setFkCurrency($moneyValueTransfer->getFkCurrency())
            );

        return $this->setPriceTypeToPriceProduct($priceTypeName, $priceProductTransfer);
    }

    /**
     * @param string $priceTypeName
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function setPriceTypeToPriceProduct(
        string $priceTypeName,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        foreach ($priceTypes as $priceTypeTransfer) {
            if ($priceTypeTransfer->getName() === $priceTypeName) {
                return $priceProductTransfer->setPriceType($priceTypeTransfer);
            }
        }

        return $priceProductTransfer;
    }

    /**
     * @param int[] $typePriceProductOfferIds
     * @param string $key
     * @param string $priceTypeName
     *
     * @return int[]
     */
    protected function getPriceProductOfferIds(
        array $typePriceProductOfferIds,
        string $key,
        string $priceTypeName
    ): array {
        $priceProductOfferIds = [];
        if (strpos($key, '[') !== false) {
            if (array_key_exists($priceTypeName, $typePriceProductOfferIds)) {
                $priceProductOfferIds[] = $typePriceProductOfferIds[$priceTypeName];
            }

            return $priceProductOfferIds;
        }

        $priceProductOfferIds = $typePriceProductOfferIds;

        return $priceProductOfferIds;
    }
}
