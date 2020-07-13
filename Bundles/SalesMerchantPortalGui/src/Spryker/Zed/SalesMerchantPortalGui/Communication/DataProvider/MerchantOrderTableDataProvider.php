<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\GuiTable\Communication\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface;

class MerchantOrderTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface
     */
    protected $salesMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        SalesMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        SalesMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        $this->salesMerchantPortalGuiRepository = $salesMerchantPortalGuiRepository;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new MerchantOrderTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantOrderCollectionTransfer = $this->salesMerchantPortalGuiRepository
            ->getMerchantOrderTableData($criteriaTransfer);

        $merchantOrderTableDataArray = [];

        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            $orderTransfer = $merchantOrderTransfer->getOrder();

            $merchantOrderTableDataArray[] = [
                MerchantOrderTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_REFERENCE => $orderTransfer->getOrderReference(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_MERCHANT_REFERENCE => $merchantOrderTransfer->getMerchantOrderReference(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_CREATED => $merchantOrderTransfer->getCreatedAt(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_CUSTOMER => $this->getCustomerData($orderTransfer),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_EMAIL => $orderTransfer->getEmail(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_NUMBER_OF_ITEMS => $merchantOrderTransfer->getMerchantOrderItemCount(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_ITEMS_STATES => $merchantOrderTransfer->getItemStates(),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_GRAND_TOTAL => $this->getGrandTotalData($merchantOrderTransfer),
                MerchantOrderGuiTableConfigurationProvider::COL_KEY_STORE => $orderTransfer->getStore(),
            ];
        }

        $paginationTransfer = $merchantOrderCollectionTransfer->getPagination();

        return (new GuiTableDataResponseTransfer())->setData($merchantOrderTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getCustomerData(OrderTransfer $orderTransfer): string
    {
        return sprintf(
            '%s %s %s',
            $orderTransfer->getSalutation(),
            $orderTransfer->getFirstName(),
            $orderTransfer->getLastName()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return string
     */
    protected function getGrandTotalData(MerchantOrderTransfer $merchantOrderTransfer): string
    {
        $moneyTransfer = (new MoneyTransfer())
            ->setAmount((string)$merchantOrderTransfer->getTotals()->getGrandTotal())
            ->setCurrency($this->currencyFacade->fromIsoCode($merchantOrderTransfer->getOrder()->getCurrencyIsoCode()));

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }
}
