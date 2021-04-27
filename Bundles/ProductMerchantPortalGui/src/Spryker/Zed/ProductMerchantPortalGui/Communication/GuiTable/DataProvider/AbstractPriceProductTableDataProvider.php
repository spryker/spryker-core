<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

abstract class AbstractPriceProductTableDataProvider extends AbstractGuiTableDataProvider
{
    protected const INDEX_PRICE_TYPE = 0;
    protected const INDEX_AMOUNT_TYPE = 2;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface
     */
    protected $productMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->productMerchantPortalGuiRepository = $productMerchantPortalGuiRepository;
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

        $priceProductTableViewCollectionTransfer = $this->productMerchantPortalGuiRepository
            ->getPriceProductTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductTableViewCollectionTransfer->getPriceProductTableViews() as $priceProductTableViewTransfer) {
            $responseData = $priceProductTableViewTransfer->toArray(true, true);

            foreach ($priceProductTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->convertIntegerToDecimal($priceValue);
            }

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $priceProductTableViewCollectionTransfer->getPaginationOrFail();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
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
