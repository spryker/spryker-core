<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use ArrayObject;
use Generated\Shared\Transfer\DateRangeGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\GuiTableBatchActionOptionsTransfer;
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
use Generated\Shared\Transfer\GuiTableRowActionOptionsTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\GuiTableSearchConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableTitleConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableTotalConfigurationTransfer;
use Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\SelectGuiTableFilterTypeOptionsTransfer;
use Spryker\Shared\GuiTable\Exception\InvalidConfigurationException;
use Spryker\Shared\GuiTable\Exception\RowActionNotFoundException;

class GuiTableConfigurationBuilder implements GuiTableConfigurationBuilderInterface
{
    /**
     * @see https://angular.io/api/common/DatePipe for details.
     *
     * @var string
     */
    protected const DEFAULT_UI_DATE_FORMAT = 'dd.MM.y';

    /**
     * @var string
     */
    protected const DEFAULT_COLUMN_BUTTON_ACTION_VARIANT = 'outline';

    /**
     * @var string
     */
    protected const DEFAULT_COLUMN_BUTTON_ACTION_SIZE = 'sm';

    /**
     * @var string
     */
    protected const DEFAULT_MODAL_OK_BUTTON_VARIANT = 'primary';

    /**
     * @var string
     */
    protected const DEFAULT_CHIP_MAX_WIDTH = '220px';

    /**
     * @var array<\Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer>
     */
    protected array $columns = [];

    /**
     * @var array<\Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer>
     */
    protected array $editableColumns = [];

    /**
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * @var array<\Generated\Shared\Transfer\GuiTableFilterTransfer>
     */
    protected array $filters = [];

    /**
     * @var array<\Generated\Shared\Transfer\GuiTableRowActionTransfer>
     */
    protected array $rowActions = [];

    /**
     * @var string|null
     */
    protected ?string $rowOnClickIdAction = null;

    /**
     * @var string|null
     */
    protected ?string $rowActionRowIdPath = null;

    /**
     * @var string|null
     */
    protected ?string $availableRowActionsPath = null;

    /**
     * @var array<\Generated\Shared\Transfer\GuiTableBatchActionTransfer>
     */
    protected array $batchActions = [];

    /**
     * @var string|null
     */
    protected ?string $batchActionRowIdPath = null;

    /**
     * @var string|null
     */
    protected ?string $availableBatchActionsPath = null;

    /**
     * @var string|null
     */
    protected ?string $noBatchActionsMessage = null;

    /**
     * @var string|null
     */
    protected ?string $dataSourceUrl = null;

    /**
     * @var array<array<string>>
     */
    protected array $dataSourceInlineData = [];

    /**
     * @var int|null
     */
    protected ?int $defaultPageSize = null;

    /**
     * @var bool
     */
    protected bool $isSearchEnabled = true;

    /**
     * @var bool
     */
    protected bool $isColumnConfiguratorEnabled = true;

    /**
     * @var string|null
     */
    protected ?string $searchPlaceholder = null;

    /**
     * @var bool|null
     */
    protected ?bool $isItemSelectionEnabled = null;

    /**
     * @var \Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer|null
     */
    protected ?GuiTableEditableConfigurationTransfer $editableConfiguration = null;

    /**
     * @var bool|null
     */
    protected ?bool $isPaginationEnabled = null;

    /**
     * @var bool
     */
    protected bool $isTotalEnabled = true;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer|null
     */
    public function getEditableConfiguration(): ?GuiTableEditableConfigurationTransfer
    {
        return $this->editableConfiguration;
    }

    /**
     * @api
     *
     * @return array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer> $columns
     *
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;

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
     * @param string|null $idAltSourceColumn
     *
     * @return $this
     */
    public function addColumnImage(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?string $idAltSourceColumn = null
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_IMAGE)
            ->setSortable($isSortable)
            ->setHideable($isHideable);

        if ($idAltSourceColumn !== null) {
            $guiTableColumnConfigurationTransfer->addTypeOption('alt', sprintf('${row.%s}', $idAltSourceColumn));
        }

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
     * @param array<mixed>|null $colorMapping
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
            ->addTypeOption('maxWidth', static::DEFAULT_CHIP_MAX_WIDTH)
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param string $text
     * @param string $actionUrl
     * @param string|null $modalTitle
     * @param string|null $modalDescription
     *
     * @return $this
     */
    public function addColumnButtonAction(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        string $text,
        string $actionUrl,
        ?string $modalTitle = null,
        ?string $modalDescription = null
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_BUTTON_ACTION)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('text', $text)
            ->addTypeOption('variant', static::DEFAULT_COLUMN_BUTTON_ACTION_VARIANT)
            ->addTypeOption('size', static::DEFAULT_COLUMN_BUTTON_ACTION_SIZE);

        if ($modalTitle !== null && $modalDescription !== null) {
            $guiTableColumnConfigurationTransfer->addTypeOption(
                'action',
                $this->getTypeOptionConfirmationRedirect($actionUrl, $modalTitle, $modalDescription),
            );
            $this->addColumn($guiTableColumnConfigurationTransfer);

            return $this;
        }

        $guiTableColumnConfigurationTransfer->addTypeOption('action', $this->getTypeOptionRedirect($actionUrl));
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
        $columnId = $guiTableColumnConfigurationTransfer->getIdOrFail();

        if (isset($this->columns[$columnId])) {
            throw new InvalidConfigurationException(sprintf('Column with id "%s" already exists', $columnId));
        }

        $this->columns[$columnId] = $guiTableColumnConfigurationTransfer;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array<int|string, string> $values select values in format of ['value1' => 'title1', 'value2' => 'title2']
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
     * @param array<\Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer> $options
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
                    ->setPlaceholderTo($placeholderTo),
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
     * @param string|null $method
     *
     * @return $this
     */
    public function addRowActionDrawerAjaxForm(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addRowAction(
            $id,
            $title,
            static::ACTION_TYPE_DRAWER,
            static::ACTION_DRAWER_COMPONENT_TYPE_AJAX_FORM,
            [
                'action' => $url,
                'method' => $method,
            ],
        );

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @return $this
     */
    public function addRowActionDrawerUrlHtmlRenderer(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addRowAction(
            $id,
            $title,
            static::ACTION_TYPE_DRAWER,
            static::ACTION_DRAWER_COMPONENT_TYPE_URL_HTML_RENDERER,
            [
                'url' => $url,
                'method' => $method,
            ],
        );

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @return $this
     */
    public function addRowActionHttp(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addRowAction(
            $id,
            $title,
            static::ACTION_TYPE_HTTP,
            null,
            [
                'url' => $url,
                'method' => $method,
            ],
        );

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
    public function addRowActionRedirect(string $id, string $title, string $url)
    {
        $this->addRowAction(
            $id,
            $title,
            static::ACTION_TYPE_REDIRECT,
            null,
            [
                'url' => $url,
            ],
        );

        return $this;
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $type
     * @param string|null $component
     * @param array<string, mixed> $options
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addRowAction(
        string $id,
        string $title,
        string $type,
        ?string $component = null,
        array $options = []
    ): void {
        if (isset($this->rowActions[$id])) {
            throw new InvalidConfigurationException(sprintf('Row action with id "%s" already exists', $id));
        }

        $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType($type);

        $this->rowActions[$id] = $guiTableRowActionTransfer;

        if ($type === static::ACTION_TYPE_HTTP) {
            $guiTableRowActionTransfer
                ->setUrl($options['url'])
                ->setMethod($options['method']);

            return;
        }

        if ($type === static::ACTION_TYPE_REDIRECT) {
            $guiTableRowActionTransfer
                ->setUrl($options['url']);
        }

        $guiTableRowActionTransfer
            ->setComponent($component)
            ->setOptions(
                (new GuiTableRowActionOptionsTransfer())
                    ->setInputs($options),
            );
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @return $this
     */
    public function addBatchActionDrawerAjaxForm(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addBatchAction(
            $id,
            $title,
            static::ACTION_TYPE_DRAWER,
            static::ACTION_DRAWER_COMPONENT_TYPE_AJAX_FORM,
            [
                'action' => $url,
                'method' => $method,
            ],
        );

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @return $this
     */
    public function addBatchActionHttp(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addBatchAction(
            $id,
            $title,
            static::ACTION_TYPE_HTTP,
            null,
            [
                'url' => $url,
                'method' => $method,
            ],
        );

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return $this
     */
    public function addBatchActionDrawerUrlHtmlRenderer(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    ) {
        $this->addBatchAction(
            $id,
            $title,
            static::ACTION_TYPE_DRAWER,
            static::ACTION_DRAWER_COMPONENT_TYPE_URL_HTML_RENDERER,
            [
                'url' => $url,
                'method' => $method,
            ],
        );

        return $this;
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $type
     * @param string|null $component
     * @param array<string, mixed> $options
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function addBatchAction(
        string $id,
        string $title,
        string $type,
        ?string $component = null,
        array $options = []
    ): void {
        if (isset($this->batchActions[$id])) {
            throw new InvalidConfigurationException(sprintf('Batch action with id "%s" already exists', $id));
        }

        $guiTableBatchActionTransfer = (new GuiTableBatchActionTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType($type);

        $this->batchActions[$id] = $guiTableBatchActionTransfer;

        if ($type === static::ACTION_TYPE_HTTP) {
            $guiTableBatchActionTransfer
                ->setUrl($options['url'])
                ->setMethod($options['method']);

            return;
        }

        $guiTableBatchActionTransfer
            ->setComponent($component)
            ->setOptions(
                (new GuiTableBatchActionOptionsTransfer())
                    ->setInputs($options),
            );
    }

    /**
     * @api
     *
     * @return array<\Generated\Shared\Transfer\GuiTableRowActionTransfer>
     */
    public function getRowActions(): array
    {
        return $this->rowActions;
    }

    /**
     * @api
     *
     * @param string $id
     *
     * @throws \Spryker\Shared\GuiTable\Exception\RowActionNotFoundException
     *
     * @return \Generated\Shared\Transfer\GuiTableRowActionTransfer
     */
    public function getRowAction(string $id): GuiTableRowActionTransfer
    {
        if (!isset($this->rowActions[$id])) {
            throw new RowActionNotFoundException($id);
        }

        return $this->rowActions[$id];
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
     * @param array<array<string>> $data
     *
     * @return $this
     */
    public function setDataSourceInlineData(array $data)
    {
        $this->dataSourceInlineData = $data;

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
     * @param bool $isEnabled
     *
     * @return $this
     */
    public function isSearchEnabled(bool $isEnabled = true)
    {
        $this->isSearchEnabled = false;

        return $this;
    }

    /**
     * @api
     *
     * @param bool $isEnabled
     *
     * @return $this
     */
    public function isColumnConfiguratorEnabled(bool $isEnabled = true)
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
     * @param bool $isPaginationEnabled
     *
     * @return $this
     */
    public function setIsPaginationEnabled(bool $isPaginationEnabled)
    {
        $this->isPaginationEnabled = $isPaginationEnabled;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $isTotalEnabled
     *
     * @return $this
     */
    public function setIsTotalEnabled(bool $isTotalEnabled)
    {
        $this->isTotalEnabled = $isTotalEnabled;

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
        $guiTableConfigurationTransfer = $this->setDataSource($guiTableConfigurationTransfer);

        if ($this->title) {
            $guiTableConfigurationTransfer->setTitle(
                (new GuiTableTitleConfigurationTransfer())
                    ->setIsEnabled(true)
                    ->setTitle($this->title),
            );
        }

        if ($this->defaultPageSize) {
            $guiTableConfigurationTransfer->setPagination(
                (new GuiTablePaginationConfigurationTransfer())->setDefaultSize($this->defaultPageSize),
            );
        }

        $guiTableConfigurationTransfer->setColumnConfigurator(
            (new GuiTableColumnConfiguratorConfigurationTransfer())->setEnabled($this->isColumnConfiguratorEnabled),
        );

        $guiTableConfigurationTransfer->setSearch(
            (new GuiTableSearchConfigurationTransfer())->setIsEnabled($this->isSearchEnabled),
        );

        $guiTableConfigurationTransfer->setTotal(
            (new GuiTableTotalConfigurationTransfer())->setIsEnabled($this->isTotalEnabled),
        );

        if ($this->searchPlaceholder) {
            $guiTableConfigurationTransfer->getSearchOrFail()
                ->addSearchOption('placeholder', $this->searchPlaceholder);
        }

        if ($this->isItemSelectionEnabled !== null) {
            $guiTableConfigurationTransfer->setItemSelection(
                (new GuiTableItemSelectionConfigurationTransfer())->setIsEnabled($this->isItemSelectionEnabled),
            );
        }

        if ($this->editableConfiguration) {
            $guiTableConfigurationTransfer->setEditable($this->editableConfiguration);
        }

        if ($this->isPaginationEnabled !== null) {
            $guiTableConfigurationTransfer->setPagination(
                (new GuiTablePaginationConfigurationTransfer())->setIsEnabled($this->isPaginationEnabled),
            );
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @api
     *
     * @param string $formInputName
     * @param array<string, mixed> $initialData
     * @param array<string, mixed>|null $addButton
     * @param array<string, mixed>|null $cancelButton
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

        $guiTableEditableInitialDataTransfer = (bool)$initialData ? $this->mapInitialDataToTransfer($initialData) : null;
        $guiTableEditableCreateConfigurationTransfer = (new GuiTableEditableCreateConfigurationTransfer())
            ->setFormInputName($formInputName)
            ->setInitialData($guiTableEditableInitialDataTransfer)
            ->setCancelButton($this->createEditableCancelButton($cancelButton))
            ->setAddButton($this->createEditableAddButton($addButton));

        $this->editableConfiguration->setCreate($guiTableEditableCreateConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $url
     * @param string $method
     * @param array<string, mixed>|null $saveButton
     * @param array<string, mixed>|null $cancelButton
     *
     * @return $this
     */
    public function enableInlineDataEditing(
        string $url,
        string $method = 'POST',
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
     * @param string $id
     * @param string $title
     * @param string $inputType
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    public function addEditableColumnInput(string $id, string $title, string $inputType = 'text', array $options = [])
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_INPUT);

        $options = array_merge([
            'type' => $inputType,
        ], $options);

        $guiTableColumnConfigurationTransfer->setTypeOptions($options);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array<int|string, mixed> $options
     * @param string|null $placeholder
     *
     * @return $this
     */
    public function addEditableColumnSelect(
        string $id,
        string $title,
        bool $isMultiselect,
        array $options,
        ?string $placeholder = null
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

        if ($placeholder) {
            $typeOptionValues['placeholder'] = $placeholder;
        }

        $guiTableColumnConfigurationTransfer->setTypeOptions($typeOptionValues);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $dependableColumn
     * @param array<string|int, mixed> $dataSetTypeOptions
     * @param array<string|int, mixed>|null $defaultTypeOptions
     *
     * @return $this
     */
    public function addInlineEditableColumnDynamic(
        string $id,
        string $title,
        string $dependableColumn,
        array $dataSetTypeOptions,
        ?array $defaultTypeOptions = null
    ) {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_DYNAMIC)
            ->setTypeOptions([
                'datasource' => [
                    'type' => static::DATA_SOURCE_TYPE_DEPENDABLE,
                    'dependsOn' => $dependableColumn,
                    'datasource' => [
                        'type' => static::DATA_SOURCE_TYPE_INLINE,
                        'data' => $dataSetTypeOptions,
                        'dependsOnContext' => [
                            'contextKey' => $dependableColumn,
                            'default' => $defaultTypeOptions,
                        ],
                    ],
                ],
            ]);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @param array<string, mixed> $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer
     */
    protected function mapInitialDataToTransfer(array $initialData): GuiTableEditableInitialDataTransfer
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
            $columnErrors = $error[GuiTableEditableDataErrorTransfer::COLUMN_ERRORS] ?? [];

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
     * @param array<string, mixed>|null $addButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableAddButton(?array $addButton): GuiTableEditableButtonTransfer
    {
        $editableButtonOptions = $this->resolveEditableButtonOptions($addButton, [
            GuiTableEditableButtonTransfer::TITLE => 'Create',
        ]);

        return $this->createEditableButton($editableButtonOptions);
    }

    /**
     * @param array<string, mixed>|null $saveButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableSaveButton(?array $saveButton): GuiTableEditableButtonTransfer
    {
        $editableButtonOptions = $this->resolveEditableButtonOptions($saveButton, [
            GuiTableEditableButtonTransfer::TITLE => 'Save',
        ]);

        return $this->createEditableButton($editableButtonOptions);
    }

    /**
     * @param array<string, mixed>|null $cancelButton
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableCancelButton(?array $cancelButton): GuiTableEditableButtonTransfer
    {
        $editableButtonOptions = $this->resolveEditableButtonOptions($cancelButton, [
            GuiTableEditableButtonTransfer::TITLE => 'Cancel',
        ]);

        return $this->createEditableButton($editableButtonOptions);
    }

    /**
     * @param array<string, mixed>|null $editableButtonOptions
     * @param array<string, mixed> $defaultOverwriteOptions
     *
     * @return array<string, ?string>
     */
    protected function resolveEditableButtonOptions(?array $editableButtonOptions, array $defaultOverwriteOptions): array
    {
        $editableButtonOptions = $editableButtonOptions ?? [];

        $defaultOptions = [
            GuiTableEditableButtonTransfer::TITLE => null,
            GuiTableEditableButtonTransfer::ICON => null,
            GuiTableEditableButtonTransfer::VARIANT => null,
            GuiTableEditableButtonTransfer::SIZE => null,
            GuiTableEditableButtonTransfer::SHAPE => null,
        ];

        return array_merge($defaultOptions, $defaultOverwriteOptions, $editableButtonOptions);
    }

    /**
     * @param array<string, ?string> $editableButtonOptions
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableButtonTransfer
     */
    protected function createEditableButton(
        array $editableButtonOptions
    ): GuiTableEditableButtonTransfer {
        return (new GuiTableEditableButtonTransfer())
            ->setTitle($editableButtonOptions[GuiTableEditableButtonTransfer::TITLE])
            ->setIcon($editableButtonOptions[GuiTableEditableButtonTransfer::ICON])
            ->setVariant($editableButtonOptions[GuiTableEditableButtonTransfer::VARIANT])
            ->setSize($editableButtonOptions[GuiTableEditableButtonTransfer::SIZE])
            ->setShape($editableButtonOptions[GuiTableEditableButtonTransfer::SHAPE]);
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
        $columnId = $guiTableColumnConfigurationTransfer->getIdOrFail();

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
            (new GuiTableFiltersConfigurationTransfer())->setIsEnabled(false),
        );

        if ($this->filters) {
            $guiTableConfigurationTransfer->getFiltersOrFail()
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
            (new GuiTableRowActionsConfigurationTransfer())->setIsEnabled(false),
        );

        if ($this->rowActions) {
            $guiTableConfigurationTransfer->getRowActionsOrFail()
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
        $guiTableBatchActionsConfigurationTransfer = (new GuiTableBatchActionsConfigurationTransfer())
            ->setIsEnabled(false);

        if ($this->batchActions) {
            $guiTableBatchActionsConfigurationTransfer
                ->setIsEnabled(true)
                ->setActions(new ArrayObject($this->batchActions))
                ->setRowIdPath($this->batchActionRowIdPath)
                ->setAvailableActionsPath($this->availableBatchActionsPath)
                ->setNoActionsMessage($this->noBatchActionsMessage);
        }

        $guiTableConfigurationTransfer->setBatchActions($guiTableBatchActionsConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function setDataSource(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableDataSourceConfigurationTransfer = new GuiTableDataSourceConfigurationTransfer();

        if ($this->dataSourceUrl) {
            $guiTableDataSourceConfigurationTransfer->setUrl($this->dataSourceUrl);
        }

        if ($this->dataSourceInlineData) {
            $guiTableDataSourceConfigurationTransfer->setData($this->dataSourceInlineData)
                ->setType(GuiTableConfigurationBuilderInterface::DATA_SOURCE_TYPE_INLINE);
        }

        $guiTableConfigurationTransfer->setDataSource($guiTableDataSourceConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $dependableColumn
     * @param string $dependableUrl
     *
     * @throws \Spryker\Shared\GuiTable\Exception\InvalidConfigurationException
     *
     * @return $this
     */
    public function addEditableColumnDynamic(string $id, string $title, string $dependableColumn, string $dependableUrl)
    {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_DYNAMIC)
            ->setTypeOptions([
                'datasource' => [
                    'type' => static::DATA_SOURCE_TYPE_DEPENDABLE,
                    'dependsOn' => $dependableColumn,
                    'datasource' => [
                        'type' => static::DATA_SOURCE_TYPE_HTTP,
                        'url' => $dependableUrl,
                    ],
                ],
            ]);

        $this->addEditableColumn($guiTableColumnConfigurationTransfer);

        return $this;
    }

    /**
     * @api
     *
     * @param string $columnId
     * @param string $displayKey
     *
     * @return $this
     */
    public function setColumnDisplayKey(string $columnId, string $displayKey)
    {
        $guiTableColumnConfigurationTransfer = $this->columns[$columnId];
        $guiTableColumnConfigurationTransfer->setDisplayKey($displayKey);
        $this->columns[$columnId] = $guiTableColumnConfigurationTransfer;

        return $this;
    }

    /**
     * @param string $actionUrl
     * @param string $modalTitle
     * @param string $modalDescription
     *
     * @return array<string, mixed>
     */
    protected function getTypeOptionConfirmationRedirect(
        string $actionUrl,
        string $modalTitle,
        string $modalDescription
    ): array {
        return [
            'type' => 'confirmation',
            'action' => [
                'type' => 'redirect',
                'url' => $actionUrl,
            ],
            'modal' => [
                'title' => $modalTitle,
                'description' => $modalDescription,
                'okVariant' => static::DEFAULT_MODAL_OK_BUTTON_VARIANT,
            ],
        ];
    }

    /**
     * @param string $actionUrl
     *
     * @return array<string, string>
     */
    protected function getTypeOptionRedirect(string $actionUrl): array
    {
        return [
            'type' => 'redirect',
            'url' => $actionUrl,
        ];
    }
}
