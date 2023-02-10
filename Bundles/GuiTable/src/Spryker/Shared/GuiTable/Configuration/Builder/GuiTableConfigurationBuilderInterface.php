<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;

interface GuiTableConfigurationBuilderInterface
{
    /**
     * @var string
     */
    public const COLUMN_TYPE_AUTOCOMPLETE = 'autocomplete';

    /**
     * @var string
     */
    public const COLUMN_TYPE_TEXT = 'text';

    /**
     * @var string
     */
    public const COLUMN_TYPE_IMAGE = 'image';

    /**
     * @var string
     */
    public const COLUMN_TYPE_DATE = 'date';

    /**
     * @var string
     */
    public const COLUMN_TYPE_CHIP = 'chip';

    /**
     * @var string
     */
    public const COLUMN_TYPE_LIST = 'list';

    /**
     * @var string
     */
    public const COLUMN_TYPE_SELECT = 'select';

    /**
     * @var string
     */
    public const COLUMN_TYPE_INPUT = 'input';

    /**
     * @var string
     */
    public const COLUMN_TYPE_DYNAMIC = 'dynamic';

    /**
     * @var string
     */
    public const FILTER_TYPE_SELECT = 'select';

    /**
     * @var string
     */
    public const FILTER_TYPE_TREE_SELECT = 'tree-select';

    /**
     * @var string
     */
    public const FILTER_TYPE_DATE_RANGE = 'date-range';

    /**
     * @var string
     */
    public const ACTION_TYPE_DRAWER = 'drawer';

    /**
     * @var string
     */
    public const ACTION_TYPE_HTTP = 'http';

    /**
     * @var string
     */
    public const ACTION_DRAWER_COMPONENT_TYPE_AJAX_FORM = 'ajax-form';

    /**
     * @var string
     */
    public const ACTION_DRAWER_COMPONENT_TYPE_URL_HTML_RENDERER = 'url-html-renderer';

    /**
     * @var string
     */
    public const DATA_SOURCE_TYPE_INLINE = 'inline';

    /**
     * @var string
     */
    public const DATA_SOURCE_TYPE_DEPENDABLE = 'dependable';

    /**
     * @var string
     */
    public const DATA_SOURCE_TYPE_HTTP = 'http';

    /**
     * @var string
     */
    public const DATA_SOURCE_TYPE_INLINE_TABLE = 'inline.table';

    /**
     * Specification:
     *  - Gets editable fields configuration.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\GuiTableEditableConfigurationTransfer|null
     */
    public function getEditableConfiguration(): ?GuiTableEditableConfigurationTransfer;

    /**
     * Specification:
     *  - Gets GuiTableColumnConfiguration transfer collection.
     *
     * @api
     *
     * @return array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer>
     */
    public function getColumns(): array;

    /**
     * Specification:
     *  - Sets GuiTableColumnConfiguration transfer collection.
     *
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer> $columns
     *
     * @return $this
     */
    public function setColumns(array $columns);

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
    );

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
    );

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
    );

    /**
     * Specification:
     *  - Gets GuiTableRowAction transfer collection.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\GuiTableRowActionTransfer>
     */
    public function getRowActions(): array;

    /**
     * Specification:
     *  - Gets GuiTableRowAction transfer by id.
     *
     * @api
     *
     * @param string $id
     *
     * @throws \Spryker\Shared\GuiTable\Exception\RowActionNotFoundException
     *
     * @return \Generated\Shared\Transfer\GuiTableRowActionTransfer
     */
    public function getRowAction(string $id): GuiTableRowActionTransfer;

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
    );

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
    );

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
    public function addFilterSelect(string $id, string $title, bool $isMultiselect, array $values);

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
    public function addFilterTreeSelect(string $id, string $title, bool $isMultiselect, array $options);

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
    );

    /**
     * Adds a new action with type form-overlay for rows.
     *
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
    );

    /**
     * Adds a new action with type html-overlay for rows.
     *
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
    );

    /**
     * Adds a new action with type url for rows.
     *
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
    );

    /**
     * Adds a new action with type `drawer` and component `ajax-form` for batch.
     *
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
    );

    /**
     * Adds a new action with type `drawer` and component `url-html-renderer` for batch.
     *
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     * @param string|null $method
     *
     * @return $this
     */
    public function addBatchActionDrawerUrlHtmlRenderer(
        string $id,
        string $title,
        string $url,
        ?string $method = null
    );

    /**
     * Adds a new action with type http for batch.
     *
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
    );

    /**
     * Sets an action ID which will be triggered when clicking on a row.
     *
     * @api
     *
     * @param string $idAction
     *
     * @return $this
     */
    public function setRowClickAction(string $idAction);

    /**
     * Sets ID of a row which will be used for replacing ${rowId} in URL (for row actions).
     * For example: https://.../${rowId} - ${rowId} will be replaced by the specified column ID.
     *
     * @api
     *
     * @param string $idPath
     *
     * @return $this
     */
    public function setRowActionRowIdPath(string $idPath);

    /**
     * Sets ID of a row which will be used for replacing ${rowId} in URL (for batch actions).
     * For example: https://.../${rowId} - ${rowId} will be replaced by the specified column ID.
     *
     * @api
     *
     * @param string $idPath
     *
     * @return $this
     */
    public function setBatchActionRowIdPath(string $idPath);

    /**
     * Sets the name of a column which contains list of available row actions.
     *
     * @api
     *
     * @param string $availableRowActionsPath
     *
     * @return $this
     */
    public function setAvailableRowActionsPath(string $availableRowActionsPath);

    /**
     * Sets the name of a column which contains list of available batch actions.
     *
     * @api
     *
     * @param string $availableBatchActionsPath
     *
     * @return $this
     */
    public function setAvailableBatchActionsPath(string $availableBatchActionsPath);

    /**
     * Sets a message which will be displayed when there are no available actions for selected rows.
     *
     * @api
     *
     * @param string $noBatchActionsMessage
     *
     * @return $this
     */
    public function setNoBatchActionsMessage(string $noBatchActionsMessage);

    /**
     * Sets URL which will be used for receiving the table data.
     *
     * @api
     *
     * @param string $url
     *
     * @return $this
     */
    public function setDataSourceUrl(string $url);

    /**
     * Sets inline data.
     *
     * @api
     *
     * @param array<array<string>> $data
     *
     * @return $this
     */
    public function setDataSourceInlineData(array $data);

    /**
     * Sets a number if rows which will be displayed by default.
     *
     * @api
     *
     * @param int $defaultPageSize
     *
     * @return $this
     */
    public function setDefaultPageSize(int $defaultPageSize);

    /**
     * @api
     *
     * @param bool $isEnabled
     *
     * @return $this
     */
    public function isSearchEnabled(bool $isEnabled = true);

    /**
     * @api
     *
     * @param bool $isEnabled
     *
     * @return $this
     */
    public function isColumnConfiguratorEnabled(bool $isEnabled = true);

    /**
     * Sets a placeholders for a search field.
     *
     * @api
     *
     * @param string $searchPlaceholder
     *
     * @return $this
     */
    public function setSearchPlaceholder(string $searchPlaceholder);

    /**
     * Enables/disables possibility to select rows by checkboxes (if enabled a new column with checkboxes will appear).
     *
     * @api
     *
     * @param bool $isItemSelectionEnabled
     *
     * @return $this
     */
    public function setIsItemSelectionEnabled(bool $isItemSelectionEnabled);

    /**
     * Sets table title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTableTitle(string $title);

    /**
     * Sets if pagination is enabled.
     *
     * @api
     *
     * @param bool $isPaginationEnabled
     *
     * @return $this
     */
    public function setIsPaginationEnabled(bool $isPaginationEnabled);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function createConfiguration(): GuiTableConfigurationTransfer;

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
    );

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
    );

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
    public function addEditableColumnInput(string $id, string $title, string $inputType = 'text', array $options = []);

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
    );

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
    public function addEditableColumnDynamic(string $id, string $title, string $dependableColumn, string $dependableUrl);

    /**
     * Specification:
     *  - Sets the display key for specified column by column ID.
     *
     * @api
     *
     * @param string $columnId
     * @param string $displayKey
     *
     * @return $this
     */
    public function setColumnDisplayKey(string $columnId, string $displayKey);
}
