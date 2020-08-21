<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTablePaginationConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface;

class MerchantOrderGuiTableConfigurationProvider extends AbstractGuiTableConfigurationProvider implements MerchantOrderGuiTableConfigurationProviderInterface
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
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addRowActionsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addPaginationToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer->setDataSource(
            (new GuiTableDataSourceConfigurationTransfer())->setUrl(static::DATA_URL)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addColumnsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $columns = new ArrayObject([
            $this->createColumnText(static::COL_KEY_REFERENCE, 'Reference', true, false),
            $this->createColumnText(static::COL_KEY_MERCHANT_REFERENCE, 'Merchant Reference', true, true),
            $this->createColumnDate(static::COL_KEY_CREATED, 'Created', true, true),
            $this->createColumnText(static::COL_KEY_CUSTOMER, 'Customer', true, true),
            $this->createColumnText(static::COL_KEY_EMAIL, 'Email', true, true),
            $this->createColumnChips(static::COL_KEY_ITEMS_STATES, 'Items States', false, true, [
                'limit' => 2,
                'typeOptions' => [
                    'color' => 'green',
                ],
            ]),
            $this->createColumnText(static::COL_KEY_GRAND_TOTAL, 'Grand Total', true, true),
            $this->createColumnText(static::COL_KEY_NUMBER_OF_ITEMS, 'No. of Items', true, true),
            $this->createColumnChip(static::COL_KEY_STORE, 'Store', false, true, [
                'color' => 'grey',
            ]),
        ]);

        $guiTableConfigurationTransfer->setColumns($columns);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addFiltersToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $filters = new ArrayObject([
            $this->createFilterDateRange('created', 'Created'),
            $this->createFilterSelect('stores', 'Stores', true, $this->getStoreOptions()),
            $this->createFilterSelect('orderItemStates', 'States', true, $this->getStatesOptions()),
        ]);
        $guiTableConfigurationTransfer->setFilters(
            (new GuiTableFiltersConfigurationTransfer())->setItems($filters)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addRowActionsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
            ->setId(static::ROW_ACTION_ID_MERCHANT_ORDER_DETAIL)
            ->setTitle('Details')
            ->setType('html-overlay')
            ->addTypeOption(
                'url',
                sprintf(
                    '/sales-merchant-portal-gui/detail?merchant-order-id=${row.%s}',
                    MerchantOrderTransfer::ID_MERCHANT_ORDER
                )
            );

        $guiTableConfigurationTransfer->setRowActions(
            (new GuiTableRowActionsConfigurationTransfer())
                ->addAction($guiTableRowActionTransfer)
                ->setClick(static::ROW_ACTION_ID_MERCHANT_ORDER_DETAIL)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addPaginationToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setPagination(
            (new GuiTablePaginationConfigurationTransfer())->setDefaultSize(25)
        );

        return $guiTableConfigurationTransfer;
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

        return $stateMachineProcessTransfer->getStateNames();
    }
}
