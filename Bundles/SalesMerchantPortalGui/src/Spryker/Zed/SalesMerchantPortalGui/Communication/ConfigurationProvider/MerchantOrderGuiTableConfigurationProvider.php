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
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
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
     * @var \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected $guiTableConfigurationBuilder;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     */
    public function __construct(
        SalesMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ) {
        $this->storeFacade = $storeFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->guiTableConfigurationBuilder = $guiTableConfigurationBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addPaginationToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());
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
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_REFERENCE, 'Reference', true, false),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_MERCHANT_REFERENCE, 'Merchant Reference', true, true),
            $this->guiTableConfigurationBuilder->createColumnDate(static::COL_KEY_CREATED, 'Created', true, false),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_CUSTOMER, 'Customer', true, true),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_EMAIL, 'Email', true, true),
            $this->guiTableConfigurationBuilder->createColumnChips(static::COL_KEY_ITEMS_STATES, 'Items States', false, true, [
                'limit' => 3,
                'typeOptions' => [
                    'color' => 'green',
                ],
            ]),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_GRAND_TOTAL, 'Grand Total', true, true),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_NUMBER_OF_ITEMS, 'No. of Items', true, true),
            $this->guiTableConfigurationBuilder->createColumnChip(static::COL_KEY_STORE, 'Store', false, true, [
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
            $this->guiTableConfigurationBuilder->createFilterDateRange('created', 'Created'),
            $this->guiTableConfigurationBuilder->createFilterSelect('stores', 'Stores', true, $this->getStoreOptions()),
            $this->guiTableConfigurationBuilder->createFilterSelect('orderItemStates', 'States', true, $this->getStatesOptions()),
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
    protected function addPaginationToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setPagination(
            (new GuiTablePaginationConfigurationTransfer())->setDefaultSize(25)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string
     */
    protected function getDefaultSortColumnKey(): string
    {
        return static::COL_KEY_REFERENCE;
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
