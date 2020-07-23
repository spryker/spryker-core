<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;

interface GuiTableConfigurationBuilderInterface
{
    public const COLUMN_TYPE_TEXT = 'text';
    public const COLUMN_TYPE_IMAGE = 'image';
    public const COLUMN_TYPE_DATE = 'date';
    public const COLUMN_TYPE_CHIP = 'chip';
    public const COLUMN_TYPE_LIST = 'list';

    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_DATE_RANGE = 'date-range';

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    public function createColumnText(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable
    ): GuiTableColumnConfigurationTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    public function createColumnImage(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable
    ): GuiTableColumnConfigurationTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param string|null $uiDateFormat
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    public function createColumnDate(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?string $uiDateFormat = null
    ): GuiTableColumnConfigurationTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param array|null $typeOptions
     * @param array|null $typeOptionsMappings
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    public function createColumnChip(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?array $typeOptions = [],
        ?array $typeOptionsMappings = []
    ): GuiTableColumnConfigurationTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param array|null $typeOptions
     * @param array|null $typeOptionsMappings
     *
     * @return \Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer
     */
    public function createColumnChips(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?array $typeOptions = [],
        ?array $typeOptionsMappings = []
    ): GuiTableColumnConfigurationTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array $values select values in format of ['value1' => 'title1', 'value2' => 'title2']
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function createFilterSelect(string $id, string $title, bool $isMultiselect, array $values): GuiTableFilterTransfer;

    /**
     * @param string $id
     * @param string $title
     * @param string|null $placeholderFrom
     * @param string|null $placeholderTo
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function createFilterDateRange(
        string $id,
        string $title,
        ?string $placeholderFrom = null,
        ?string $placeholderTo = null
    ): GuiTableFilterTransfer;
}
