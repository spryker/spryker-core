<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Builder;

use Generated\Shared\Transfer\DateRangeGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\OptionSelectGuiTableFilterTypeOptionsTransfer;
use Generated\Shared\Transfer\SelectGuiTableFilterTypeOptionsTransfer;

class GuiTableConfigurationBuilder implements GuiTableConfigurationBuilderInterface
{
    /**
     * @see https://angular.io/api/common/DatePipe for details.
     */
    protected const DEFAULT_UI_DATE_FORMAT = 'dd.MM.y';

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
    ): GuiTableColumnConfigurationTransfer {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_TEXT)
            ->setSortable($isSortable)
            ->setHideable($isHideable);
    }

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
    ): GuiTableColumnConfigurationTransfer {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_IMAGE)
            ->setSortable($isSortable)
            ->setHideable($isHideable);
    }

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
    ): GuiTableColumnConfigurationTransfer {
        return (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_DATE)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('format', $uiDateFormat ?? static::DEFAULT_UI_DATE_FORMAT);
    }

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
    ): GuiTableColumnConfigurationTransfer {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_CHIP)
            ->setSortable($isSortable)
            ->setHideable($isHideable);

        foreach ($typeOptions as $key => $typeOption) {
            $guiTableColumnConfigurationTransfer->addTypeOption($key, $typeOption);
        }

        foreach ($typeOptionsMappings as $key => $typeOptionsMapping) {
            $guiTableColumnConfigurationTransfer->addTypeOptionMapping($key, $typeOptionsMapping);
        }

        return $guiTableColumnConfigurationTransfer;
    }

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
    ): GuiTableColumnConfigurationTransfer {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId($id)
            ->setTitle($title)
            ->setType(static::COLUMN_TYPE_LIST)
            ->setSortable($isSortable)
            ->setHideable($isHideable)
            ->addTypeOption('type', static::COLUMN_TYPE_CHIP);

        foreach ($typeOptions as $key => $typeOption) {
            $guiTableColumnConfigurationTransfer->addTypeOption($key, $typeOption);
        }

        foreach ($typeOptionsMappings as $key => $typeOptionsMapping) {
            $guiTableColumnConfigurationTransfer->addTypeOptionMapping($key, $typeOptionsMapping);
        }

        return $guiTableColumnConfigurationTransfer;
    }

    /**
     * @param string $id
     * @param string $title
     * @param bool $isMultiselect
     * @param array $values select values in format of ['value1' => 'title1', 'value2' => 'title2']
     *
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function createFilterSelect(
        string $id,
        string $title,
        bool $isMultiselect,
        array $values
    ): GuiTableFilterTransfer {
        $typeOptionTransfers = (new SelectGuiTableFilterTypeOptionsTransfer())->setMultiselect($isMultiselect);

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
    public function createFilterDateRange(
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
}
