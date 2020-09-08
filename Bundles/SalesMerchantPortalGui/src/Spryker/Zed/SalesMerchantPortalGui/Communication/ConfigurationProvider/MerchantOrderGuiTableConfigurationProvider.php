<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface;

class MerchantOrderGuiTableConfigurationProvider implements MerchantOrderGuiTableConfigurationProviderInterface
{
    public const COL_KEY_REFERENCE = 'reference';
    public const COL_KEY_MERCHANT_REFERENCE = 'merchantReference';
    public const COL_KEY_CREATED = 'created';
    public const COL_KEY_CUSTOMER = 'customer';
    public const COL_KEY_EMAIL = 'Email';
    public const COL_KEY_ITEMS_STATES = 'itemsStates';
    public const COL_KEY_GRAND_TOTAL = 'grandTotal';
    public const COL_KEY_NUMBER_OF_ITEMS = 'numberOfItems';
    public const COL_KEY_STORE = 'store';

    protected const ROW_ACTION_ID_MERCHANT_ORDER_DETAIL = 'merchant-order-detail';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\Controller\OrdersController::tableDataAction()
     */
    protected const DATA_URL = '/sales-merchant-portal-gui/orders/table-data';

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(
        SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        GuiTableFactoryInterface $guiTableFactory
    ) {
        $this->storeFacade = $storeFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->guiTableFactory = $guiTableFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setDefaultPageSize(25);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_REFERENCE, 'Reference', true, false)
            ->addColumnText(static::COL_KEY_MERCHANT_REFERENCE, 'Merchant Reference', true, true)
            ->addColumnDate(static::COL_KEY_CREATED, 'Created', true, true)
            ->addColumnText(static::COL_KEY_CUSTOMER, 'Customer', true, true)
            ->addColumnText(static::COL_KEY_EMAIL, 'Email', true, true)
            ->addColumnChips(static::COL_KEY_ITEMS_STATES, 'Items States', false, true, 2, 'green')
            ->addColumnText(static::COL_KEY_GRAND_TOTAL, 'Grand Total', true, true)
            ->addColumnText(static::COL_KEY_NUMBER_OF_ITEMS, 'No. of Items', true, true)
            ->addColumnChip(static::COL_KEY_STORE, 'Store', true, true, 'grey');

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionOpenPageOverlay(
            static::ROW_ACTION_ID_MERCHANT_ORDER_DETAIL,
            'Details',
            sprintf(
                '/sales-merchant-portal-gui/detail?merchant-order-id=${row.%s}',
                MerchantOrderTransfer::ID_MERCHANT_ORDER
            )
        )->setRowClickAction(static::ROW_ACTION_ID_MERCHANT_ORDER_DETAIL);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterDateRange('created', 'Created')
            ->addFilterSelect('stores', 'Stores', true, $this->getStoreOptions())
            ->addFilterSelect('orderItemStates', 'States', true, $this->getStatesOptions());

        return $guiTableConfigurationBuilder;
    }

    /**
     * @return string[]
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeOptions[$storeTransfer->getName()] = $storeTransfer->getName();
        }

        return $storeOptions;
    }

    /**
     * @return string[]
     */
    protected function getStatesOptions(): array
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setIdMerchant($idMerchant);
        $stateMachineProcessTransfer = $this->merchantOmsFacade->getMerchantOmsProcessByMerchant($merchantCriteriaTransfer);

        return array_map(function (string $stateName) {
            return mb_convert_case($stateName, MB_CASE_TITLE);
        }, $stateMachineProcessTransfer->getStateNames());
    }
}
