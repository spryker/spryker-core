<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface GuiTableConfigurationBuilderInterface
{
    public const COLUMN_TYPE_TEXT = 'text';
    public const COLUMN_TYPE_IMAGE = 'image';
    public const COLUMN_TYPE_DATE = 'date';
    public const COLUMN_TYPE_CHIP = 'chip';
    public const COLUMN_TYPE_LIST = 'list';
    public const COLUMN_TYPE_SELECT = 'select';
    public const COLUMN_TYPE_INPUT = 'input';

    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_TREE_SELECT = 'tree-select';
    public const FILTER_TYPE_DATE_RANGE = 'date-range';

    public const ACTION_TYPE_FORM_OVERLAY = 'form-overlay';
    public const ACTION_TYPE_HTML_OVERLAY = 'html-overlay';
    public const ACTION_TYPE_URL = 'url';

    public const KEY_EDITABLE_FORM_INPUT_NAME = 'formInputName';
    public const KEY_EDITABLE_INITIAL_DATA = 'initialData';
    public const KEY_EDITABLE_ADD_BUTTON = 'addButton';
    public const KEY_EDITABLE_CANCEL_BUTTON = 'cancelButton';
    public const KEY_EDITABLE_SAVE_BUTTON = 'saveButton';
    public const KEY_EDITABLE_URL = 'url';

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
     *
     * @return $this
     */
    public function addColumnImage(
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
     * @param string $id
     * @param string $title
     *
     * @return $this
     */
    public function addEditableColumnInput(
        string $id,
        string $title
    );

    /**
     * @param string $id
     * @param string $title
     * @param array $options
     * @param bool $options
     *
     * @return $this
     */
    public function addEditableColumnSelect(
        string $id,
        string $title,
        array $options
    );


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
    public function addFilterSelect(string $id, string $title, bool $isMultiselect, array $values);

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
     *
     * @return $this
     */
    public function addRowActionOpenFormOverlay(
        string $id,
        string $title,
        string $url
    );

    /**
     * Adds a new action with type html-overlay for rows.
     *
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addRowActionOpenPageOverlay(
        string $id,
        string $title,
        string $url
    );

    /**
     * Adds a new action with type url for rows.
     *
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addRowActionUrl(
        string $id,
        string $title,
        string $url
    );

    /**
     * Adds a new batch action with type url for rows.
     *
     * @api
     *
     * @param string $id
     * @param string $title
     * @param string $url
     *
     * @return $this
     */
    public function addBatchActionUrl(string $id, string $title, string $url);

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
     * @param string $title
     *
     * @return $this
     */
    public function setTableTitle(string $title);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function createConfiguration(): GuiTableConfigurationTransfer;

    /**
     * @param bool $isTableEditable
     *
     * @return $this
     */
    public function setTableEditable(bool $isTableEditable = false);

    /**
     * @param string $formInputName
     *
     * @return $this
     */
    public function setEditableCreateActionFormInputName(string $formInputName);

    /**
     * @param array $initialData
     *
     * @return $this
     */
    public function setEditableCreateActionInitialData(array $initialData);

    /**
     * @param string|null $title
     * @param string|null $icon
     *
     * @return $this
     */
    public function addEditableCreateActionAddButton(?string $title = '', ?string $icon = '');

    /**
     * @param string|null $title
     * @param string|null $icon
     *
     * @return $this
     */
    public function addEditableCreateActionCancelButton(?string $title = '', ?string $icon = '');

    /**
     * @param string $method
     * @param string $url
     *
     * @return $this
     */
    public function setEditableUpdateActionUrl(string $method, string $url);

    /**
     * @param string|null $title
     * @param string|null $icon
     *
     * @return $this
     */
    public function addEditableUpdateActionAddButton(?string $title = '', ?string $icon = '');

    /**
     * @param string|null $title
     * @param string|null $icon
     *
     * @return $this
     */
    public function addEditableUpdateActionCancelButton(?string $title = '', ?string $icon = '');
}
