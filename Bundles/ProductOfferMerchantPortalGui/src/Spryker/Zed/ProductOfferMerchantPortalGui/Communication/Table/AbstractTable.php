<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table;

use ArrayObject;
use Generated\Shared\Transfer\DateRangeGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\SelectGuiTableFilterTypeOptionsTransfer;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
abstract class AbstractTable
{
    use BundleConfigResolverAwareTrait;

    protected const CONFIG_COLUMNS = 'columns';
    protected const CONFIG_DATA_URL = 'dataUrl';
    protected const CONFIG_PAGINATION = 'pagination';
    protected const CONFIG_FILTERS = 'filters';
    protected const CONFIG_ROW_ACTIONS = 'rowActions';
    protected const CONFIG_SEARCH = 'search';
    protected const CONFIG_COLUMN_CONFIGURATOR = 'columnConfigurator';
    protected const CONFIG_ITEM_SELECTION = 'itemselection';
    protected const CONFIG_SYNC_STATE_URL = 'syncStateUrl';
    protected const CONFIG_ENABLED = 'enabled';
    protected const CONFIG_ACTIONS = 'actions';
    protected const CONFIG_SIZES = 'sizes';
    protected const CONFIG_ITEMS = 'items';

    public const COLUMN_TYPE_TEXT = 'text';
    public const COLUMN_TYPE_IMAGE = 'image';
    public const COLUMN_TYPE_DATE = 'date';
    public const COLUMN_TYPE_DATE_RANGE = 'date_range';
    public const COLUMN_TYPE_CHIP = 'chip';

    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_DATE_RANGE = 'date_range';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getData(Request $request): array
    {
        $guiTableDataTransfer = $this->provideTableData($request);

        return $guiTableDataTransfer->toArray(true, true);
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        $guiTableConfigurationTransfer = $this->buildTableConfiguration();
        $guiTableConfigurationTransfer = $this->translateConfiguration($guiTableConfigurationTransfer);

        return [
            static::CONFIG_COLUMNS => $this->prepareColumnsConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_DATA_URL => $guiTableConfigurationTransfer->getDataUrl(),
            static::CONFIG_PAGINATION => $this->preparePaginationData($guiTableConfigurationTransfer),
            static::CONFIG_FILTERS => $this->prepareFiltersConfigurationData($guiTableConfigurationTransfer),
            static::CONFIG_ROW_ACTIONS => $this->prepareRowActions($guiTableConfigurationTransfer),
            static::CONFIG_SEARCH => $this->prepareSearchData($guiTableConfigurationTransfer),
            static::CONFIG_COLUMN_CONFIGURATOR => $this->prepareColumnConfiguratorData($guiTableConfigurationTransfer),
            static::CONFIG_ITEM_SELECTION => $this->prepareItemSelectionData($guiTableConfigurationTransfer),
            static::CONFIG_SYNC_STATE_URL => $this->prepareSyncStateUrlData($guiTableConfigurationTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareColumnsConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $columnsData = [];

        foreach ($guiTableConfigurationTransfer->getColumns() as $columnTransfer) {
            $columnsData[] = $columnTransfer->modifiedToArray(true, true);
        }

        return $columnsData;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function preparePaginationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $sizes = !empty($guiTableConfigurationTransfer->getAvailablePageSizes())
            ? $guiTableConfigurationTransfer->getAvailablePageSizes()
            : $this->getConfig()->getTableDefaultAvailablePageSizes();

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsPageSizeEnabled() ?? true,
            static::CONFIG_SIZES => $sizes,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareFiltersConfigurationData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $filtersItems = [];

        foreach ($guiTableConfigurationTransfer->getFilters() as $filterTransfer) {
            $filtersItems[] = $filterTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsFiltersEnabled() ?? true,
            static::CONFIG_ITEMS => $filtersItems,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareRowActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $actions = [];

        foreach ($guiTableConfigurationTransfer->getRowActions() as $rowActionTransfer) {
            $actions[] = $rowActionTransfer->toArray(true, true);
        }

        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsRowActionsEnabled() ?? true,
            static::CONFIG_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareColumnConfiguratorData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsColumnConfiguratorEnabled() ?? true,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareItemSelectionData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsItemSelectionEnabled() ?? true,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareSyncStateUrlData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return [
            static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsSyncStateUrlEnabled() ?? true,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array
     */
    protected function prepareSearchData(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        return $guiTableConfigurationTransfer->getSearch() + [
                static::CONFIG_ENABLED => $guiTableConfigurationTransfer->getIsSearchEnabled() ?? true,
            ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $guiTableConfigurationTransfer = $this->translateColumns($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateFilters($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateRowActions($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->translateSearch($guiTableConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateColumns(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableColumnConfigurationTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
            $translatedGuiTableColumnConfigurationTransfers[] = $this->translateColumn($guiTableColumnConfigurationTransfer);
        }

        $guiTableConfigurationTransfer->setColumns($translatedGuiTableColumnConfigurationTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function translateColumn(
        GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
    ): GuiTableColumnConfigurationTransfer {
        $columnTitle = $guiTableColumnConfigurationTransfer->getTitle();

        if ($columnTitle) {
            $guiTableColumnConfigurationTransfer->setTitle($this->translate($columnTitle));
        }

        return $guiTableColumnConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateFilters(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableFilterTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getFilters() as $guiTableFilterTransfer) {
            $translatedGuiTableFilterTransfers[] = $this->translateFilter($guiTableFilterTransfer);
        }

        $guiTableConfigurationTransfer->setFilters($translatedGuiTableFilterTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableFilterTransfer $guiTableFilterTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    protected function translateFilter(GuiTableFilterTransfer $guiTableFilterTransfer): GuiTableFilterTransfer
    {
        $filterTitle = $guiTableFilterTransfer->getTitle();

        if ($filterTitle) {
            $guiTableFilterTransfer->setTitle($this->translate($filterTitle));
        }

        if ($guiTableFilterTransfer->getType() === static::FILTER_TYPE_SELECT) {
            /** @var \Generated\Shared\Transfer\SelectGuiTableFilterTypeOptionsTransfer $selectTypeOptions */
            $selectTypeOptions = $guiTableFilterTransfer->getTypeOptions();
            foreach ($selectTypeOptions->getValues() as $selectOption) {
                $translatedTitle = $this->translate($selectOption->getTitle());
                $selectOption->setTitle($translatedTitle);
            }
        }

        return $guiTableFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateRowActions(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $translatedGuiTableRowActionTransfers = new ArrayObject();

        foreach ($guiTableConfigurationTransfer->getRowActions() as $guiTableRowActionTransfer) {
            $translatedGuiTableRowActionTransfers[] = $this->translateRowAction($guiTableRowActionTransfer);
        }

        $guiTableConfigurationTransfer->setRowActions($translatedGuiTableRowActionTransfers);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableRowActionTransfer $guiTableRowActionTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableRowActionTransfer
     */
    protected function translateRowAction(GuiTableRowActionTransfer $guiTableRowActionTransfer): GuiTableRowActionTransfer
    {
        $rowActionTitle = $guiTableRowActionTransfer->getTitle();

        if ($rowActionTitle) {
            $guiTableRowActionTransfer->setTitle($this->translate($rowActionTitle));
        }

        return $guiTableRowActionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function translateSearch(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $search = $guiTableConfigurationTransfer->getSearch();

        foreach ($search as $key => $searchOption) {
            $search[$key] = $this->translate($searchOption);
        }

        $guiTableConfigurationTransfer->setSearch($search);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function translate(string $key): string
    {
        return $this->translatorFacade->trans($key);
    }

    /**
     * @param string $id
     * @param string $title
     * @param bool $sortable
     * @param bool $hidable
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function createColumnText(string $id, string $title, bool $sortable, bool $hidable): GuiTableColumnConfigurationTransfer
    {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_TEXT)
            ->setSortable($sortable)
            ->setHideable($hidable);
    }

    /***
     * @param string $id
     * @param string $title
     * @param bool $sortable
     * @param bool $hidable
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function createColumnImage(string $id, string $title, bool $sortable, bool $hidable): GuiTableColumnConfigurationTransfer
    {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_IMAGE)
            ->setSortable($sortable)
            ->setHideable($hidable);
    }

    /**
     * @param string $id
     * @param string $title
     * @param bool $sortable
     * @param bool $hidable
     * @param string|null $dateFormat
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function createColumnDate(
        string $id,
        string $title,
        bool $sortable,
        bool $hidable,
        ?string $dateFormat = null
    ): GuiTableColumnConfigurationTransfer {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_DATE)
            ->setSortable($sortable)
            ->setHideable($hidable)
            ->addTypeOption('format', $dateFormat ?? $this->getConfig()->getTableDefaultUiDateFormat());
    }

    /***
     * @param string $id
     * @param string $title
     * @param bool $sortable
     * @param bool $hidable
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    protected function createColumnChip(string $id, string $title, bool $sortable, bool $hidable): GuiTableColumnConfigurationTransfer
    {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_CHIP)
            ->setSortable($sortable)
            ->setHideable($hidable);
    }

    /**
     * @param string $id
     * @param string $title
     * @param bool $multiselect
     * @param array $values select values in form of ['value1' => 'title1', 'value2' => 'title2' ]
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    protected function createFilterSelect(string $id, string $title, bool $multiselect, array $values): GuiTableFilterTransfer
    {
        $typeOptionTransfers = (new SelectGuiTableFilterTypeOptionsTransfer())
            ->setMultiselect($multiselect);

        foreach ($values as $value => $optionTitle) {
            $optionTransfer = (new OptionSelectGuiTableFilterTypeOptionsTransfer())
                ->setValue($value)
                ->setTitle($optionTitle);
            $typeOptionTransfers->addValue($optionTransfer);
        }

        return (new GuiTableFilterTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::FILTER_TYPE_SELECT)
            ->setTypeOptions($typeOptionTransfers);
    }

    /**
     * @param string $id
     * @param string $title
     * @param string|null $placeholderFrom
     * @param string|null $placeholderTo
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    protected function createFilterDateRange(
        string $id,
        string $title,
        ?string $placeholderFrom = null,
        ?string $placeholderTo = null
    ): GuiTableFilterTransfer {
        $guiTableFilterTransfer = (new GuiTableFilterTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::FILTER_TYPE_DATE_RANGE);

        if ($placeholderFrom || $placeholderTo) {
            $guiTableFilterTransfer->setTypeOptions(
                (new DateRangeGuiTableFilterTypeOptionsTransfer())
                    ->setPlaceholderFrom($placeholderFrom)
                    ->setPlaceholderTo($placeholderTo)
            );
        }

        return $guiTableFilterTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    abstract protected function provideTableData(Request $request): GuiTableDataTransfer;

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    abstract protected function buildTableConfiguration(): GuiTableConfigurationTransfer;
}
