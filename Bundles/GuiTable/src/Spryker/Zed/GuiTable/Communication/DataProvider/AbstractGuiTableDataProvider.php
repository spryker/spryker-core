<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\GuiTable\Communication\Exception\InvalidCriteriaPropertyException;

abstract class AbstractGuiTableDataProvider implements GuiTableDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    abstract protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function getData(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): GuiTableDataResponseTransfer
    {
        $criteriaTransfer = $this->createCriteria($guiTableDataRequestTransfer);
        $criteriaTransfer = $this->mapFiltersToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapPagingToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapSortingToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapLocaleToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapSearchTermToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);

        return $this->fetchData($criteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\GuiTable\Communication\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapFiltersToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $criteriaTransfer): AbstractTransfer
    {
        foreach ($guiTableDataRequestTransfer->getFilters() as $filterName => $filterValue) {
            $setter = 'setFilter' . $filterName;

            if (!method_exists($criteriaTransfer, $setter)) {
                throw new InvalidCriteriaPropertyException($criteriaTransfer, $filterName);
            }

            $criteriaTransfer->$setter($filterValue);
        }

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\GuiTable\Communication\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapPagingToCriteria(
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer,
        AbstractTransfer $criteriaTransfer
    ): AbstractTransfer {
        $pageSetter = 'setPage';
        $pageSizeSetter = 'setPageSize';
        if (!method_exists($criteriaTransfer, $pageSizeSetter) && !method_exists($criteriaTransfer, $pageSetter)) {
            throw new InvalidCriteriaPropertyException($criteriaTransfer, 'page|pageSize');
        }

        $criteriaTransfer->setPage($guiTableDataRequestTransfer->getPage());
        $criteriaTransfer->setPageSize($guiTableDataRequestTransfer->getPageSize());

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\GuiTable\Communication\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapSortingToCriteria(
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer,
        AbstractTransfer $criteriaTransfer
    ): AbstractTransfer {
        $orderSetter = 'setOrderBy';
        $orderDirectionSetter = 'setOrderDirection';
        if (!method_exists($criteriaTransfer, $orderSetter) && !method_exists($criteriaTransfer, $orderDirectionSetter)) {
            throw new InvalidCriteriaPropertyException($criteriaTransfer, 'orderBy|orderDirection');
        }

        $criteriaTransfer->setOrderBy($guiTableDataRequestTransfer->getOrderBy());
        $criteriaTransfer->setOrderDirection(
            $guiTableDataRequestTransfer->getOrderDirection()
                ? strtoupper($guiTableDataRequestTransfer->getOrderDirection())
                : null
        );

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapLocaleToCriteria(
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer,
        AbstractTransfer $criteriaTransfer
    ): AbstractTransfer {
        $localeSetter = 'setIdLocale';

        if (!method_exists($criteriaTransfer, $localeSetter)) {
            return $criteriaTransfer;
        }

        $criteriaTransfer->setIdLocale($guiTableDataRequestTransfer->getIdLocale());

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapSearchTermToCriteria(
        GuiTableDataRequestTransfer $guiTableDataRequestTransfer,
        AbstractTransfer $criteriaTransfer
    ): AbstractTransfer {
        $searchTermSetter = 'setSearchTerm';

        if (!method_exists($criteriaTransfer, $searchTermSetter)) {
            return $criteriaTransfer;
        }

        $criteriaTransfer->setSearchTerm($guiTableDataRequestTransfer->getSearchTerm());

        return $criteriaTransfer;
    }
}
