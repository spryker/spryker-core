<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use ArrayObject;
use Generated\Shared\Transfer\DateRangeGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\GuiTableBatchActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableBatchActionTransfer;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableColumnConfiguratorConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableButtonTransfer;
use Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableCreateConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\GuiTableEditableUpdateConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableUrlTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\GuiTableItemSelectionConfigurationTransfer;
use Generated\Shared\Transfer\GuiTablePaginationConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\GuiTableSearchConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableTitleConfigurationTransfer;
use Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\SelectGuiTableFilterTypeOptionsTransfer;
use Spryker\Shared\GuiTable\Exception\InvalidConfigurationException;

class GuiTableConfigurationBuilder implements GuiTableConfigurationBuilderInterface
{
    /**
     * @see https://angular.io/api/common/DatePipe for details.
     */
    protected const DEFAULT_UI_DATE_FORMAT = 'dd.MM.y';

    /**
     * @var \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer[]
     */
    protected $columns;

    /**
     * @var \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer[]
     */
    protected $editableColumns;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \Generated\Shared\Transfer\GuiTableFilterTransfer[]
     */
    protected $filters = [];

    /**
     * @var \Generated\Shared\Transfer\GuiTableRowActionTransfer[]
     */
    protected $rowActions = [];

    /**
     * @var string
     */
    protected $rowOnClickIdAction;

    /**
     * @var string
     */
    protected $rowActionRowIdPath;

    /**
     * @var string
     */
    protected $availableRowActionsPath;

    /**
     * @var \Generated\Shared\Transfer\GuiTableBatchActionTransfer[]
     */
    protected $batchActions = [];

    /**
     * @var string
     */
    protected $batchActionRowIdPath;

    /**
     * @var string
     */
    protected $availableBatchActionsPath;

    /**
     * @var string
     */
    protected $noBatchActionsMessage;

    /**
     * @var string
     */
    protected $dataSourceUrl;

    /**
     * @var int
     */
    protected $defaultPageSize;

    /**
     * @var bool
     */
    protected $isSearchEnabled = true;

    /**
     * @var bool
     */
    protected $isColumnConfiguratorEnabled = true;

    /**
     * @var string
     */
    protected $searchPlaceholder;

    /**
     * @var bool
     */
    protected $isItemSelectionEnabled;

    /**
     * @var \Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer|null
     */
    protected $editableConfiguration;

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     *
     * @return $this
     */
    public function addColumnText(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_TEXT)
            ->setSortable($isSortable)
            ->setHideable($isHideable);

        $this->addColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     *
     * @return $this
     */
    public function addColumnImage(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_IMAGE)
            ->setSortable($isSortable)
            ->setHideable($isHideable);

        $this->addColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param string|null $uiDateFormat
     *
     * @return $this
     */
    public function addColumnDate(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?string $uiDateFormat = null
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_DATE)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('format', $uiDateFormat ?? static::DEFAULT_UI_DATE_FORMAT);

        $this->addColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param string|null $color
     * @param mixed[]|null $colorMapping
     *
     * @return $this
     */
    public function addColumnChip(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?string $color,
        ?array $colorMapping = []
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_CHIP)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('color', $color)
            ->addTypeOptionMapping('color', $colorMapping);

        $this->addColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param int|null $limit
     * @param string|null $color
     *
     * @return $this
     */
    public function addColumnListChip(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?int $limit,
        ?string $color
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_LIST)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('type', static::COLUMN_TYPE_CHIP)
            ->addTypeOption('limit', $limit);

        if ($color) {
            $guiTableColumnConfigurationTransfer->addTypeOption('typeOptions', [
                'color' => $color,
            ]);
        }

        $this->addColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addColumn(GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer): void
    {
        $columnId = $guiTableColumnConfigurationTransfer->getId();

        if (isset($this->columns[$columnId])) {
            throw new InvalidConfigurationException(sprintf('Column with id "%s" already exists', $columnId));
        }

        $this->columns[$columnId] = $guiTableColumnConfigurationTransfer;
    }

    /**
     * @api
     *
     * @phpstan-param array<int, string> $values
     *
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param string[] $values select values in format of ['value1' => 'title1', 'value2' => 'title2']
     *
     * @return $this
     */
    public function addFilterSelect(
        string $id,
        string $title,
        bool $isMultiselect,
        array $values
    ) {
        $typeOptionTransfers = (new SelectGuiTableFilterTypeOptionsTransfer())->setMultiselect($isMultiselect);

        foreach ($values as $value => $optionTitle) {
            $optionTransfer = (new OptionSelectGuiTableFilterTypeOptionsTransfer())
                ->setValue((string)$value)
                ->setTitle($optionTitle);
            $typeOptionTransfers->addValue($optionTransfer);
        }

        $this->filters[] = (new GuiTableFilterTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::FILTER_TYPE_SELECT)
            ->setTypeOptions($typeOptionTransfers);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param \Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer[] $options
     *
     * @return $this
     */
    public function addFilterTreeSelect(string $id, string $title, bool $isMultiselect, array $options)
    {
        $typeOptionTransfers = (new SelectGuiTableFilterTypeOptionsTransfer())->setMultiselect($isMultiselect);

        foreach ($options as $optionTransfer) {
            $typeOptionTransfers->addValue($optionTransfer);
        }

        $this->filters[] = (new GuiTableFilterTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::FILTER_TYPE_TREE_SELECT)
            ->setTypeOptions($typeOptionTransfers);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string|null $placeholderFrom
     * @param string|null $placeholderTo
     *
     * @return $this
     */
    public function addFilterDateRange(
        string $id,
        string $title,
        ?string $placeholderFrom = null,
        ?string $placeholderTo = null
    ) {
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

        $this->filters[] = $guiTableFilterTransfer;

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addRowActionOpenFormOverlay(string $id, string $title, string $url)
    {
        $this->addRowAction($id, $title, static::ACTION_TYPE_FORM_OVERLAY, ['url' => $url]);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addRowActionOpenPageOverlay(string $id, string $title, string $url)
    {
        $this->addRowAction($id, $title, static::ACTION_TYPE_HTML_OVERLAY, ['url' => $url]);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addRowActionUrl(string $id, string $title, string $url)
    {
        $this->addRowAction($id, $title, static::ACTION_TYPE_URL, ['url' => $url]);

        return $this;
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $type
     * @param string[] $options
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addRowAction(string $id, string $title, string $type, array $options): void
    {
        if (isset($this->rowActions[$id])) {
            throw new InvalidConfigurationException(sprintf('Row action with id "%s" already exists', $id));
        }

        $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType($type)
            ->setTypeOptions($options);

        $this->rowActions[$id] = $guiTableRowActionTransfer;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addBatchActionUrl(string $id, string $title, string $url)
    {
        $this->addBatchAction($id, $title, static::ACTION_TYPE_URL, ['url' => $url]);

        return $this;
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $type
     * @param string[] $options
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addBatchAction(string $id, string $title, string $type, array $options): void
    {
        if (isset($this->batchActions[$id])) {
            throw new InvalidConfigurationException(sprintf('Batch action with id "%s" already exists', $id));
        }

        $guiTableBatchActionTransfer = (new GuiTableBatchActionTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType($type)
            ->setTypeOptions($options);

        $this->batchActions[$id] = $guiTableBatchActionTransfer;
    }

    /**
     * @api
     *
     * @param string $idAction
     *
     * @return $this
     */
    public function setRowClickAction(string $idAction)
    {
        $this->rowOnClickIdAction = $idAction;

        return $this;
    }

    /**
     * @api
     *
     * @param string $idPath
     *
     * @return $this
     */
    public function setRowActionRowIdPath(string $idPath)
    {
        $this->rowActionRowIdPath = $idPath;

        return $this;
    }

    /**
     * @api
     *
     * @param string $idPath
     *
     * @return $this
     */
    public function setBatchActionRowIdPath(string $idPath)
    {
        $this->batchActionRowIdPath = $idPath;

        return $this;
    }

    /**
     * @api
     *
     * @param string $availableRowActionsPath
     *
     * @return $this
     */
    public function setAvailableRowActionsPath(string $availableRowActionsPath)
    {
        $this->availableRowActionsPath = $availableRowActionsPath;

        return $this;
    }

    /**
     * @api
     *
     * @param string $availableBatchActionsPath
     *
     * @return $this
     */
    public function setAvailableBatchActionsPath(string $availableBatchActionsPath)
    {
        $this->availableBatchActionsPath = $availableBatchActionsPath;

        return $this;
    }

    /**
     * @api
     *
     * @param string $noBatchActionsMessage
     *
     * @return $this
     */
    public function setNoBatchActionsMessage(string $noBatchActionsMessage)
    {
        $this->noBatchActionsMessage = $noBatchActionsMessage;

        return $this;
    }

    /**
     * @api
     *
     * @param string $url
     *
     * @return $this
     */
    public function setDataSourceUrl(string $url)
    {
        $this->dataSourceUrl = $url;

        return $this;
    }

    /**
     * @api
     *
     * @param int $defaultPageSize
     *
     * @return $this
     */
    public function setDefaultPageSize(int $defaultPageSize)
    {
        $this->defaultPageSize = $defaultPageSize;

        return $this;
    }

    /**
     * @api
     *
     * @return $this
     */
    public function disableSearch()
    {
        $this->isSearchEnabled = false;

        return $this;
    }

    /**
     * @api
     *
     * @return $this
     */
    public function disableColumnConfigurator()
    {
        $this->isColumnConfiguratorEnabled = false;

        return $this;
    }

    /**
     * @api
     *
     * @param string $searchPlaceholder
     *
     * @return $this
     */
    public function setSearchPlaceholder(string $searchPlaceholder)
    {
        $this->searchPlaceholder = $searchPlaceholder;

        return $this;
    }

    /**
     * @api
     *
     * @param bool $isItemSelectionEnabled
     *
     * @return $this
     */
    public function setIsItemSelectionEnabled(bool $isItemSelectionEnabled)
    {
        $this->isItemSelectionEnabled = $isItemSelectionEnabled;

        return $this;
    }

    /**
     * @api
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTableTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @api
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function createConfiguration(): GuiTableConfigurationTransfer
    {
        if (!$this->columns) {
            throw new InvalidConfigurationException('Table must have at least one column');
        }

        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();

        $guiTableConfigurationTransfer->setColumns(new ArrayObject($this->columns));
        $guiTableConfigurationTransfer = $this->setFilters($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->setRowActions($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->setBatchActions($guiTableConfigurationTransfer);

        if ($this->title) {
            $guiTableConfigurationTransfer->setTitle(
                (new GuiTableTitleConfigurationTransfer())
                    ->setIsEnabled(true)
                    ->setTitle($this->title)
            );
        }

        if ($this->dataSourceUrl) {
            $guiTableConfigurationTransfer->setDataSource(
                (new GuiTableDataSourceConfigurationTransfer())->setUrl($this->dataSourceUrl)
            );
        }

        if ($this->defaultPageSize) {
            $guiTableConfigurationTransfer->setPagination(
                (new GuiTablePaginationConfigurationTransfer())->setDefaultSize($this->defaultPageSize)
            );
        }

        $guiTableConfigurationTransfer->setColumnConfigurator(
            (new GuiTableColumnConfiguratorConfigurationTransfer())->setEnabled($this->isColumnConfiguratorEnabled)
        );

        $guiTableConfigurationTransfer->setSearch(
            (new GuiTableSearchConfigurationTransfer())->setIsEnabled($this->isSearchEnabled)
        );

        if ($this->searchPlaceholder) {
            $guiTableConfigurationTransfer->getSearch()
                ->addSearchOption('placeholder', $this->searchPlaceholder);
        }

        if ($this->isItemSelectionEnabled !== null) {
            $guiTableConfigurationTransfer->setItemSelection(
                (new GuiTableItemSelectionConfigurationTransfer())->setIsEnabled($this->isItemSelectionEnabled)
            );
        }

        if ($this->editableConfiguration) {
            $guiTableConfigurationTransfer->setEditable($this->editableConfiguration);
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @api
     *
     * @phpstan-param array<mixed> $initialData
     * @phpstan-param array<mixed> $addButton
     * @phpstan-param array<mixed> $cancelButton
     *
     * @param string $formInputName
     * @param array|null $initialData
     * @param array|null $addButton
     * @param array|null $cancelButton
     *
     * @return $this
     */
    public function enableAddingNewRows(
        string $formInputName,
        array $initialData = [],
        ?array $addButton = null,
        ?array $cancelButton = null
    ) {
        if (!$this->editableConfiguration) {
            $this->editableConfiguration = (new GuiTableEditableConfigurationTransfer())->setEnabled(true);
        }

        $initialData = !empty($initialData) ? $this->mapInitialData($initialData) : null;
        $guiTableEditableCreateConfigurationTransfer = (new GuiTableEditableCreateConfigurationTransfer())
            ->setFormInputName($formInputName)
            ->setInitialData($initialData)
            ->setCancelButton($this->createEditableCancelButton($cancelButton))
            ->setAddButton($this->createEditableAddButton($addButton));

        $this->editableConfiguration->setCreate($guiTableEditableCreateConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @phpstan-param array<mixed> $saveButton
     * @phpstan-param array<mixed> $cancelButton
     *
     * @param string $url
     * @param string|null $method
     * @param array|null $saveButton
     * @param array|null $cancelButton
     *
     * @return $this
     */
    public function enableInlineDataEditing(
        string $url,
        ?string $method = 'POST',
        ?array $saveButton = null,
        ?array $cancelButton = null
    ) {
        if (!$this->editableConfiguration) {
            $this->editableConfiguration = (new GuiTableEditableConfigurationTransfer())->setEnabled(true);
        }

        $guiTableEditableUpdateConfigurationTransfer = (new GuiTableEditableUpdateConfigurationTransfer())
            ->setUrl($this->createEditableUrl($url, $method))
            ->setSaveButton($this->createEditableSaveButton($saveButton))
            ->setCancelButton($this->createEditableCancelButton($cancelButton));

        $this->editableConfiguration->setUpdate($guiTableEditableUpdateConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @phpstan-param array<string, mixed> $options
     *
     * @param string $id
     * @param string $title
     * @param array|null $options
     *
     * @return $this
     */
    public function addEditableColumnInput(string $id, string $title, ?array $options = [])
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_INPUT);

        $guiTableColumnConfigurationTransfer->setTypeOptions($options);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @phpstan-param array<mixed> $options
     *
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array $options
     *
     * @return $this
     */
    public function addEditableColumnSelect(
        string $id,
        string $title,
        bool $isMultiselect,
        array $options
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_SELECT);

        $typeOptionValues = [];

        foreach ($options as $value => $optionTitle) {
            $typeOptionValues['options'][] = [
                'title' => $optionTitle,
                'value' => $value,
            ];
        }

        $guiTableColumnConfigurationTransfer->setTypeOptions($typeOptionValues);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer
     */
    protected function mapInitialData(array $initialData): GuiTableEditableInitialDataTransfer
    {
        $guiTableEditableInitialDataTransfer = (new GuiTableEditableInitialDataTransfer())
            ->fromArray($initialData, true);
        $errors = $initialData[GuiTableEditableInitialDataTransfer::ERRORS] ?? null;

        if (!$errors) {
            return $guiTableEditableInitialDataTransfer;
        }

        $guiTableEditableDataErrorTransfers = [];

        foreach ($errors as $error) {
            $rowError = $error[GuiTableEditableDataErrorTransfer::ROW_ERROR] ?? null;
            $columnErrors = $error[GuiTableEditableDataErrorTransfer::COLUMN_ERRORS] ?? null;

            $guiTableEditableDataErrorTransfers[] = (new GuiTableEditableDataErrorTransfer())
                ->setRowError($rowError)
                ->setColumnErrors($columnErrors);
        }

        return $guiTableEditableInitialDataTransfer->setErrors(new ArrayObject($guiTableEditableDataErrorTransfers));
    }

    /**
     * @param string $url
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableUrlTransfer
     */
    protected function createEditableUrl(string $url, string $method): GuiTableEditableUrlTransfer
    {
        return (new GuiTableEditableUrlTransfer())
            ->setMethod($method)
            ->setUrl($url);
    }

    /**
     * @phpstan-param array<mixed> $addButton
     *
     * @param array|null $addButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableAddButton(?array $addButton): GuiTableEditableButtonTransfer
    {
        $title = $addButton[GuiTableEditableButtonTransfer::TITLE] ?? 'Create';
        $icon = $addButton[GuiTableEditableButtonTransfer::ICON] ?? null;

        return $this->createEditableButton($title, $icon);
    }

    /**
     * @phpstan-param array<mixed> $saveButton
     *
     * @param array|null $saveButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableSaveButton(?array $saveButton): GuiTableEditableButtonTransfer
    {
        $title = $saveButton[GuiTableEditableButtonTransfer::TITLE] ?? 'Save';
        $icon = $saveButton[GuiTableEditableButtonTransfer::ICON] ?? null;

        return $this->createEditableButton($title, $icon);
    }

    /**
     * @phpstan-param array<mixed> $cancelButton
     *
     * @param array|null $cancelButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableCancelButton(?array $cancelButton)
    {
        $title = $cancelButton[GuiTableEditableButtonTransfer::TITLE] ?? 'Cancel';
        $icon = $cancelButton[GuiTableEditableButtonTransfer::ICON] ?? null;

        return $this->createEditableButton($title, $icon);
    }

    /**
     * @param string|null $title
     * @param string|null $icon
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableButton(?string $title, ?string $icon): GuiTableEditableButtonTransfer
    {
        return (new GuiTableEditableButtonTransfer())
            ->setTitle($title)
            ->setIcon($icon);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addEditableColumn(GuiTableColumnConfigurationTransfer $guiTableColumnConfigurationTransfer): void
    {
        $columnId = $guiTableColumnConfigurationTransfer->getId();

        if (isset($this->editableColumns[$columnId])) {
            throw new InvalidConfigurationException(sprintf('Editable column with id "%s" already exists', $columnId));
        }

        if (!$this->editableConfiguration) {
            $this->editableConfiguration = (new GuiTableEditableConfigurationTransfer())->setEnabled(true);
        }

        $this->editableConfiguration->addColumn($guiTableColumnConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function setFilters(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setFilters(
            (new GuiTableFiltersConfigurationTransfer())->setIsEnabled(false)
        );

        if ($this->filters) {
            $guiTableConfigurationTransfer->getFilters()
                ->setIsEnabled(true)
                ->setItems(new ArrayObject($this->filters));
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function setRowActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setRowActions(
            (new GuiTableRowActionsConfigurationTransfer())->setIsEnabled(false)
        );

        if ($this->rowActions) {
            $guiTableConfigurationTransfer->getRowActions()
                ->setIsEnabled(true)
                ->setActions(new ArrayObject($this->rowActions))
                ->setClick($this->rowOnClickIdAction)
                ->setRowIdPath($this->rowActionRowIdPath)
                ->setAvailableActionsPath($this->availableRowActionsPath);
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function setBatchActions(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setBatchActions(
            (new GuiTableBatchActionsConfigurationTransfer())->setIsEnabled(false)
        );

        if ($this->batchActions) {
            $guiTableConfigurationTransfer->getBatchActions()
                ->setIsEnabled(true)
                ->setActions(new ArrayObject($this->batchActions))
                ->setRowIdPath($this->batchActionRowIdPath)
                ->setAvailableActionsPath($this->availableBatchActionsPath)
                ->setNoActionsMessage($this->noBatchActionsMessage);
        }

        return $guiTableConfigurationTransfer;
    }
}
