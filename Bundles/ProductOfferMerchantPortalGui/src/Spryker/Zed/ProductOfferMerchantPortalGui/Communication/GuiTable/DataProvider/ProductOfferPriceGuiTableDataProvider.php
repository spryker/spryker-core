<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;

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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface
     */
    protected $productOfferMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param int|null $idProductOffer
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ?int $idProductOffer = null
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->idProductOffer = $idProductOffer;
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        /** @var int $idMerchant */
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

        $priceProductOfferTableViewCollectionTransfer = $this->productOfferMerchantPortalGuiRepository
            ->getProductOfferPriceTableData($criteriaTransfer);

        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductOfferTableViewCollectionTransfer->getPriceProductOfferTableViews() as $priceProductOfferTableViewTransfer) {
            $responseData = $priceProductOfferTableViewTransfer->toArray();

            foreach ($priceProductOfferTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->moneyFacade->convertIntegerToDecimal((int)$priceValue);
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
}
