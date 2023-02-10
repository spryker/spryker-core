<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;

class PriceProductOfferTableDataMapper implements PriceProductOfferTableDataMapperInterface
{
    /**
     * @var int
     */
    protected const PAGINATION_FIRST_PAGE = 1;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    protected ColumnIdCreatorInterface $columnIdCreator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface $columnIdCreator
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ColumnIdCreatorInterface $columnIdCreator
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->columnIdCreator = $columnIdCreator;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    public function mapPriceProductTransfersToPriceProductOfferTableViewCollectionTransfer(
        array $priceProductTransfers,
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
    ): PriceProductOfferTableViewCollectionTransfer {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();
        $storeNamesIndexedByIdStore = $this->getStoreNamesIndexedByIdStore();

        $priceProductOfferTableViewTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $storeName = $storeNamesIndexedByIdStore[$priceProductTransfer->getMoneyValueOrFail()->getFkStoreOrFail()];
            $currencyCode = $priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail()->getCodeOrFail();

            $priceProductOfferTableViewRowKey = $this->createPriceProductOfferTableViewRowKey(
                $storeName,
                $currencyCode,
                $priceProductTransfer->getVolumeQuantityOrFail(),
            );

            if (!array_key_exists($priceProductOfferTableViewRowKey, $priceProductOfferTableViewTransfers)) {
                $prices = $this->preparePrices($priceProductTransfer, $priceTypeTransfers);

                $priceProductOfferTableViewTransfer = $this->createPriceProductOfferTableViewTransfer(
                    $priceProductTransfer,
                    $prices,
                    $storeName,
                    $currencyCode,
                );

                $priceProductOfferTableViewTransfers[$priceProductOfferTableViewRowKey] = $priceProductOfferTableViewTransfer;

                continue;
            }

            $priceProductOfferTableViewTransfers[$priceProductOfferTableViewRowKey] = $this
                ->mergePriceProductTransferToPriceProductOfferTableViewTransfer(
                    $priceProductOfferTableViewTransfers[$priceProductOfferTableViewRowKey],
                    $priceProductTransfer,
                    $priceTypeTransfers,
                );
        }

        $paginationTransfer = $this->mapPriceProductOfferTableViewTransfersToPaginationTransfer(
            $priceProductOfferTableViewTransfers,
            new PaginationTransfer(),
        );

        $priceProductOfferTableViewCollectionTransfer
            ->setPriceProductOfferTableViews(
                new ArrayObject($priceProductOfferTableViewTransfers),
            )
            ->setPagination($paginationTransfer);

        return $priceProductOfferTableViewCollectionTransfer;
    }

    /**
     * @param string $storeName
     * @param string $currencyCode
     * @param int $volumeQuantity
     *
     * @return string
     */
    protected function createPriceProductOfferTableViewRowKey(
        string $storeName,
        string $currencyCode,
        int $volumeQuantity
    ): string {
        return sprintf(
            '%s-%s-%d',
            $storeName,
            $currencyCode,
            $volumeQuantity,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array<string, int> $prices
     * @param string $storeName
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewTransfer
     */
    protected function createPriceProductOfferTableViewTransfer(
        PriceProductTransfer $priceProductTransfer,
        array $prices,
        string $storeName,
        string $currencyCode
    ): PriceProductOfferTableViewTransfer {
        return (new PriceProductOfferTableViewTransfer())
            ->setStore($storeName)
            ->setCurrency($currencyCode)
            ->setPrices($prices)
            ->addPriceProductOfferId($priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductOffer())
            ->setTypePriceProductOfferIds($this->prepareTypePriceProductOfferId($priceProductTransfer))
            ->setVolumeQuantity($priceProductTransfer->getVolumeQuantity());
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductOfferTableViewTransfer> $priceProductOfferTableViewTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function mapPriceProductOfferTableViewTransfersToPaginationTransfer(
        array $priceProductOfferTableViewTransfers,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        $countTotal = count($priceProductOfferTableViewTransfers);

        return $paginationTransfer
            ->setFirstPage(static::PAGINATION_FIRST_PAGE)
            ->setNbResults($countTotal);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewTransfer $priceProductOfferTableViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewTransfer
     */
    protected function mergePriceProductTransferToPriceProductOfferTableViewTransfer(
        PriceProductOfferTableViewTransfer $priceProductOfferTableViewTransfer,
        PriceProductTransfer $priceProductTransfer,
        array $priceTypeTransfers
    ): PriceProductOfferTableViewTransfer {
        $pricesAdditional = $this->preparePrices($priceProductTransfer, $priceTypeTransfers);
        $typePriceProductOfferIdAdditional = $this->prepareTypePriceProductOfferId($priceProductTransfer);

        $pricesCombined = array_merge(
            $priceProductOfferTableViewTransfer->getPrices(),
            $pricesAdditional,
        );
        $typePriceProductOfferIdsCombined = sprintf(
            '%s,%s',
            $priceProductOfferTableViewTransfer->getTypePriceProductOfferIdsOrFail(),
            $typePriceProductOfferIdAdditional,
        );

        $priceProductOfferTableViewTransfer
            ->setPrices($pricesCombined)
            ->addPriceProductOfferId(
                $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductOffer(),
            )
            ->setTypePriceProductOfferIds($typePriceProductOfferIdsCombined);

        return $priceProductOfferTableViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @return array<string, int>
     */
    protected function preparePrices(PriceProductTransfer $priceProductTransfer, array $priceTypeTransfers): array
    {
        $prices = [];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeNameFromPriceProduct = mb_strtolower(
                $priceProductTransfer->getPriceTypeOrFail()->getNameOrFail(),
            );
            $priceTypeName = mb_strtolower((string)$priceTypeTransfer->getNameOrFail());

            if ($priceTypeName !== $priceTypeNameFromPriceProduct) {
                continue;
            }

            $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

            if ($moneyValueTransfer->getGrossAmount() !== null) {
                $columnId = $this->columnIdCreator->createGrossAmountColumnId($priceTypeName);
                $prices[$columnId] = $moneyValueTransfer->getGrossAmountOrFail();
            }

            if ($moneyValueTransfer->getNetAmount() !== null) {
                $columnId = $this->columnIdCreator->createNetAmountColumnId($priceTypeName);
                $prices[$columnId] = $moneyValueTransfer->getNetAmountOrFail();
            }
        }

        return $prices;
    }

    /**
     * @return array<int, string>
     */
    protected function getStoreNamesIndexedByIdStore(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeIdToNameMapping = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeIdToNameMapping[$storeTransfer->getIdStoreOrFail()] = $storeTransfer->getNameOrFail();
        }

        return $storeIdToNameMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    protected function prepareTypePriceProductOfferId(PriceProductTransfer $priceProductTransfer): string
    {
        return sprintf(
            '%s:%d',
            $priceProductTransfer->getPriceTypeOrFail()->getNameOrFail(),
            $priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductOfferOrFail(),
        );
    }
}
