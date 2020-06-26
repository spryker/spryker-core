<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Translator;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface;

class ConfigurationTranslator implements ConfigurationTranslatorInterface
{
    /**
     * @var \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(GuiTableToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function translateConfiguration(
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
        $guiTableFiltersConfigurationTransfer = $guiTableConfigurationTransfer->getFilters();

        if (!$guiTableFiltersConfigurationTransfer->getIsEnabled()) {
            return $guiTableConfigurationTransfer;
        }

        $translatedGuiTableFilterTransfers = new ArrayObject();

        foreach ($guiTableFiltersConfigurationTransfer->getItems() as $guiTableFilterTransfer) {
            $translatedGuiTableFilterTransfers[] = $this->translateFilter($guiTableFilterTransfer);
        }

        $guiTableFiltersConfigurationTransfer->setItems($translatedGuiTableFilterTransfers);
        $guiTableConfigurationTransfer->setFilters($guiTableFiltersConfigurationTransfer);

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

        if ($guiTableFilterTransfer->getType() === AbstractGuiTableConfigurationProvider::FILTER_TYPE_SELECT) {
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
        $guiTableRowActionsConfigurationTransfer = $guiTableConfigurationTransfer->getRowActions();

        if (!$guiTableRowActionsConfigurationTransfer->getIsEnabled()) {
            return $guiTableConfigurationTransfer;
        }

        $translatedGuiTableRowActionTransfers = new ArrayObject();

        foreach ($guiTableRowActionsConfigurationTransfer->getActions() as $guiTableRowActionTransfer) {
            $translatedGuiTableRowActionTransfers[] = $this->translateRowAction($guiTableRowActionTransfer);
        }

        $guiTableRowActionsConfigurationTransfer->setActions($translatedGuiTableRowActionTransfers);
        $guiTableConfigurationTransfer->setRowActions($guiTableRowActionsConfigurationTransfer);

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
        $guiTableSearchConfigurationTransfer = $guiTableConfigurationTransfer->getSearch();

        if (!$guiTableSearchConfigurationTransfer->getIsEnabled()) {
            return $guiTableConfigurationTransfer;
        }

        $search = $guiTableSearchConfigurationTransfer->getSearch();

        foreach ($search as $key => $searchOption) {
            $search[$key] = $this->translate($searchOption);
        }

        $guiTableSearchConfigurationTransfer->setSearch($search);
        $guiTableConfigurationTransfer->setSearch($guiTableSearchConfigurationTransfer);

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
}
