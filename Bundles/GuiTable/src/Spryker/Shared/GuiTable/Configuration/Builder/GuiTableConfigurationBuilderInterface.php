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

    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_DATE_RANGE = 'date-range';

    public const ROW_ACTION_TYPE_FORM_OVERLAY = 'form-overlay';
    public const ROW_ACTION_TYPE_HTML_OVERLAY = 'html-overlay';

    /**
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
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param array|null $typeOptions
     * @param array|null $typeOptionsMappings
     *
     * @return $this
     */
    public function addColumnChip(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?array $typeOptions = [],
        ?array $typeOptionsMappings = []
    );

    /**
     * @param string $id
     * @param string $title
     * @param bool $isSortable
     * @param bool $isHideable
     * @param array|null $typeOptions
     * @param array|null $typeOptionsMappings
     *
     * @return $this
     */
    public function addColumnChips(
        string $id,
        string $title,
        bool $isSortable,
        bool $isHideable,
        ?array $typeOptions = [],
        ?array $typeOptionsMappings = []
    );

    /**
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array $values select values in format of ['value1' => 'title1', 'value2' => 'title2']
     *
     * @return $this
     */
    public function addFilterSelect(string $id, string $title, bool $isMultiselect, array $values);

    /**
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
     * @param string $idAction
     *
     * @return $this
     */
    public function setRowClickAction(string $idAction);

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setDataSourceUrl(string $url);

    /**
     * @param int $defaultPageSize
     *
     * @return $this
     */
    public function setDefaultPageSize(int $defaultPageSize);

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function createConfiguration(): GuiTableConfigurationTransfer;
}
