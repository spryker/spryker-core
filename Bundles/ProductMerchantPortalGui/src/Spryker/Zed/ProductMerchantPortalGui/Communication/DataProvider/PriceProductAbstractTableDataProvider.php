<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;

class PriceProductAbstractTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var int
     */
    protected $idProductAbstract;

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
     * @param int $idProductAbstract
     * @param \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        int $idProductAbstract,
        ProductMerchantPortalGuiRepositoryInterface $productMerchantPortalGuiRepository,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->idProductAbstract = $idProductAbstract;
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
            ->setIdProductAbstract($this->idProductAbstract)
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductAbstractTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $priceProductAbstractTableViewCollectionTransfer = $this->productMerchantPortalGuiRepository
            ->getPriceProductAbstractTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();

        foreach ($priceProductAbstractTableViewCollectionTransfer->getPriceProductAbstractTableViews() as $priceProductAbstractTableViewTransfer) {
            $responseData = $priceProductAbstractTableViewTransfer->toArray();

            foreach ($priceProductAbstractTableViewTransfer->getPrices() as $priceType => $priceValue) {
                $responseData[$priceType] = $this->moneyFacade->convertIntegerToDecimal((int)$priceValue);
            }

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        $paginationTransfer = $priceProductAbstractTableViewCollectionTransfer->getPagination();

        return $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }
}
