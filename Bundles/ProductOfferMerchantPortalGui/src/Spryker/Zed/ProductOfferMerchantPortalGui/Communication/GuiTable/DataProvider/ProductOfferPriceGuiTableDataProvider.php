<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapperInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;

class ProductOfferPriceGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var int|null
     */
    protected $idProductOffer;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapperInterface
     */
    protected $priceProductOfferTableDataMapper;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    protected $priceProductReader;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorterInterface
     */
    protected $priceProductOfferTableViewSorter;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Mapper\PriceProductOfferTableDataMapperInterface $priceProductOfferTableDataMapper
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Reader\PriceProductReaderInterface $priceProductReader
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Sorter\PriceProductOfferTableViewSorterInterface $priceProductOfferTableViewSorter
     * @param int|null $idProductOffer
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        PriceProductOfferTableDataMapperInterface $priceProductOfferTableDataMapper,
        PriceProductReaderInterface $priceProductReader,
        PriceProductOfferTableViewSorterInterface $priceProductOfferTableViewSorter,
        ?int $idProductOffer = null
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->idProductOffer = $idProductOffer;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductOfferTableDataMapper = $priceProductOfferTableDataMapper;
        $this->priceProductReader = $priceProductReader;
        $this->priceProductOfferTableViewSorter = $priceProductOfferTableViewSorter;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchantOrFail();

        return (new PriceProductOfferTableCriteriaTransfer())
            ->setIdProductOffer($this->idProductOffer)
            ->setIdMerchant($idMerchant);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        if (!$criteriaTransfer->getIdProductOffer()) {
            return new GuiTableDataResponseTransfer();
        }

        $criteriaTransfer = $this->replaceSortingFields($criteriaTransfer);

        $priceProductOfferTableViewCollectionTransfer = $this
            ->createPriceProductOfferTableViewCollectionTransfer($criteriaTransfer);

        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductOfferTableViewCollectionTransfer->getPriceProductOfferTableViews() as $priceProductOfferTableViewTransfer) {
            $responseData = $priceProductOfferTableViewTransfer->toArray();

            foreach ($priceProductOfferTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->convertIntegerToDecimal($priceValue);
            }

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $priceProductOfferTableViewCollectionTransfer->getPaginationOrFail();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    protected function createPriceProductOfferTableViewCollectionTransfer(
        PriceProductOfferTableCriteriaTransfer $criteriaTransfer
    ): PriceProductOfferTableViewCollectionTransfer {
        $priceProductTransfers = $this->priceProductReader
            ->getPriceProductTransfers((
                (new PriceProductOfferCriteriaTransfer())
                    ->setStoreIds($criteriaTransfer->getFilterInStores())
                    ->setCurrencyIds($criteriaTransfer->getFilterInCurrencies())
                    ->setIdProductOffer($criteriaTransfer->getIdProductOffer())
            ));

        $priceProductOfferTableViewCollectionTransfer = $this->priceProductOfferTableDataMapper
            ->mapPriceProductTransfersToPriceProductOfferTableViewCollectionTransfer(
                $priceProductTransfers,
                new PriceProductOfferTableViewCollectionTransfer()
            );

        $this->priceProductOfferTableViewSorter->sortPriceProductOfferTableViews(
            $priceProductOfferTableViewCollectionTransfer,
            $criteriaTransfer
        );

        $this->updatePaginationTransfer(
            $priceProductOfferTableViewCollectionTransfer,
            $criteriaTransfer
        );

        $this->applyPagination($priceProductOfferTableViewCollectionTransfer);

        return $priceProductOfferTableViewCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function updatePaginationTransfer(
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer,
        PriceProductOfferTableCriteriaTransfer $criteriaTransfer
    ): PaginationTransfer {
        $count = $priceProductOfferTableViewCollectionTransfer->getPriceProductOfferTableViews()->count();

        return $priceProductOfferTableViewCollectionTransfer->getPaginationOrFail()
            ->setPage($criteriaTransfer->getPage())
            ->setMaxPerPage($criteriaTransfer->getPageSize())
            ->setLastPage((int)($count / $criteriaTransfer->getPageSize()));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableViewCollectionTransfer
     */
    protected function applyPagination(
        PriceProductOfferTableViewCollectionTransfer $priceProductOfferTableViewCollectionTransfer
    ): PriceProductOfferTableViewCollectionTransfer {
        $priceProductOfferTableViews = $priceProductOfferTableViewCollectionTransfer
            ->getPriceProductOfferTableViews()
            ->getArrayCopy();

        $paginationTransfer = $priceProductOfferTableViewCollectionTransfer->getPaginationOrFail();

        $positionStart = ($paginationTransfer->getPage() - 1) * $paginationTransfer->getMaxPerPage();

        $priceProductOfferTableViewsOnCurrentPage = array_slice(
            $priceProductOfferTableViews,
            $positionStart,
            $paginationTransfer->getMaxPerPage()
        );

        $priceProductOfferTableViewCollectionTransfer->setPriceProductOfferTableViews(
            new ArrayObject($priceProductOfferTableViewsOnCurrentPage)
        );

        return $priceProductOfferTableViewCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer
     */
    protected function replaceSortingFields(PriceProductOfferTableCriteriaTransfer $criteriaTransfer): PriceProductOfferTableCriteriaTransfer
    {
        /** @var string $orderByField */
        $orderByField = $criteriaTransfer->getOrderBy();

        if (!$orderByField) {
            return $criteriaTransfer;
        }

        if (strpos($orderByField, '[') === false) {
            return $criteriaTransfer;
        }

        /** @var string $orderByField */
        $orderByField = str_replace(']', '', $orderByField);
        $orderByField = explode('[', $orderByField);

        if ($orderByField[2] === MoneyValueTransfer::NET_AMOUNT) {
            return $criteriaTransfer->setOrderBy($orderByField[0] . '_net');
        }

        if ($orderByField[2] === MoneyValueTransfer::GROSS_AMOUNT) {
            return $criteriaTransfer->setOrderBy($orderByField[0] . '_gross');
        }

        return $criteriaTransfer;
    }

    /**
     * @param mixed $value
     *
     * @return float|null
     */
    protected function convertIntegerToDecimal($value): ?float
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return $this->moneyFacade->convertIntegerToDecimal((int)$value);
    }
}
