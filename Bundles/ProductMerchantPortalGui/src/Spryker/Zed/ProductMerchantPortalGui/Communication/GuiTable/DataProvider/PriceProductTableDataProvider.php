<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;


use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

abstract class PriceProductTableDataProvider extends AbstractGuiTableDataProvider
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
        return (new PriceProductAbstractTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $criteriaTransfer = $this->replacePriceSortingFields($criteriaTransfer);

        $priceProductAbstractTableViewCollectionTransfer = $this->productMerchantPortalGuiRepository
            ->getPriceProductAbstractTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductAbstractTableViewCollectionTransfer->getPriceProductAbstractTableViews() as $priceProductAbstractTableViewTransfer) {
            $responseData = $priceProductAbstractTableViewTransfer->toArray(true, true);

            foreach ($priceProductAbstractTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->convertIntegerToDecimal($priceValue);
            }

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $priceProductAbstractTableViewCollectionTransfer->getPaginationOrFail();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer
     */
    protected function replacePriceSortingFields(
        PriceProductAbstractTableCriteriaTransfer $priceProductAbstractTableCriteriaTransfer
    ): PriceProductAbstractTableCriteriaTransfer {
        /** @var string $orderByField */
        $orderByField = $priceProductAbstractTableCriteriaTransfer->getOrderBy();

        if (!$orderByField) {
            return $priceProductAbstractTableCriteriaTransfer;
        }

        if (strpos($orderByField, '[') === false) {
            return $priceProductAbstractTableCriteriaTransfer;
        }

        /** @var string $orderByField */
        $orderByField = str_replace(']', '', $orderByField);
        $orderByField = explode('[', $orderByField);

        if ($orderByField[static::INDEX_AMOUNT_TYPE] === MoneyValueTransfer::NET_AMOUNT) {
            return $priceProductAbstractTableCriteriaTransfer->setOrderBy($orderByField[static::INDEX_PRICE_TYPE] . '_net');
        }

        if ($orderByField[static::INDEX_AMOUNT_TYPE] === MoneyValueTransfer::GROSS_AMOUNT) {
            return $priceProductAbstractTableCriteriaTransfer->setOrderBy($orderByField[static::INDEX_PRICE_TYPE] . '_gross');
        }

        return $priceProductAbstractTableCriteriaTransfer;
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