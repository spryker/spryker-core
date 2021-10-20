<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;

abstract class AbstractPriceProductTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var int
     */
    protected const INDEX_PRICE_TYPE = 0;

    /**
     * @var int
     */
    protected const INDEX_AMOUNT_TYPE = 2;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface
     */
    protected $priceProductTableDataMapper;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface
     */
    protected $priceProductTableViewSorter;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    protected $priceProductReader;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface $priceProductReader
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductTableDataMapperInterface $priceProductTableDataMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\Sorter\PriceProductTableViewSorterInterface $priceProductTableViewSorter
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        PriceProductReaderInterface $priceProductReader,
        PriceProductTableDataMapperInterface $priceProductTableDataMapper,
        PriceProductTableViewSorterInterface $priceProductTableViewSorter,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->priceProductReader = $priceProductReader;
        $this->priceProductTableDataMapper = $priceProductTableDataMapper;
        $this->priceProductTableViewSorter = $priceProductTableViewSorter;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new PriceProductTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $criteriaTransfer = $this->replacePriceSortingFields($criteriaTransfer);

        $priceProductTransfers = $this->priceProductReader->getPriceProducts($criteriaTransfer);

        $priceProductTableViewCollectionTransfer = $this->priceProductTableDataMapper
            ->mapPriceProductTransfersToPriceProductTableViewCollectionTransfer(
                $priceProductTransfers,
                new PriceProductTableViewCollectionTransfer()
            );
        $priceProductTableViewCollectionTransfer = $this->priceProductTableViewSorter
            ->sortPriceProductTableViews($priceProductTableViewCollectionTransfer, $criteriaTransfer);

        $paginationTransfer = $this->updatePaginationTransfer(
            $priceProductTableViewCollectionTransfer,
            $criteriaTransfer
        );
        $priceProductTableViewCollectionTransfer = $this->applyPagination($priceProductTableViewCollectionTransfer);

        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductTableViewCollectionTransfer->getPriceProductTableViews() as $priceProductTableViewTransfer) {
            $responseData = $priceProductTableViewTransfer->toArray(true, true);

            foreach ($priceProductTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->convertIntegerToDecimal($priceValue);
            }

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function updatePaginationTransfer(
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer,
        PriceProductTableCriteriaTransfer $criteriaTransfer
    ): PaginationTransfer {
        $count = $priceProductTableViewCollectionTransfer->getPriceProductTableViews()->count();

        return $priceProductTableViewCollectionTransfer->getPaginationOrFail()
            ->setPage($criteriaTransfer->getPage())
            ->setMaxPerPage($criteriaTransfer->getPageSize())
            ->setLastPage((int)($count / $criteriaTransfer->getPageSize()));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableViewCollectionTransfer
     */
    protected function applyPagination(
        PriceProductTableViewCollectionTransfer $priceProductTableViewCollectionTransfer
    ): PriceProductTableViewCollectionTransfer {
        $priceProductOfferTableViews = $priceProductTableViewCollectionTransfer
            ->getPriceProductTableViews()
            ->getArrayCopy();

        $paginationTransfer = $priceProductTableViewCollectionTransfer->getPaginationOrFail();

        $positionStart = ($paginationTransfer->getPage() - 1) * $paginationTransfer->getMaxPerPage();

        $priceProductTableViewsOnCurrentPage = array_slice(
            $priceProductOfferTableViews,
            $positionStart,
            $paginationTransfer->getMaxPerPage()
        );

        $priceProductTableViewCollectionTransfer->setPriceProductTableViews(
            new ArrayObject($priceProductTableViewsOnCurrentPage)
        );

        return $priceProductTableViewCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer
     */
    protected function replacePriceSortingFields(
        PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
    ): PriceProductTableCriteriaTransfer {
        /** @var string $orderByField */
        $orderByField = $priceProductTableCriteriaTransfer->getOrderBy();

        if (!$orderByField) {
            return $priceProductTableCriteriaTransfer;
        }

        if (strpos($orderByField, '[') === false) {
            return $priceProductTableCriteriaTransfer;
        }

        /** @var string $orderByField */
        $orderByField = str_replace(']', '', $orderByField);
        $orderByField = explode('[', $orderByField);

        if ($orderByField[static::INDEX_AMOUNT_TYPE] === MoneyValueTransfer::NET_AMOUNT) {
            return $priceProductTableCriteriaTransfer->setOrderBy($orderByField[static::INDEX_PRICE_TYPE] . '_net');
        }

        if ($orderByField[static::INDEX_AMOUNT_TYPE] === MoneyValueTransfer::GROSS_AMOUNT) {
            return $priceProductTableCriteriaTransfer->setOrderBy($orderByField[static::INDEX_PRICE_TYPE] . '_gross');
        }

        return $priceProductTableCriteriaTransfer;
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
