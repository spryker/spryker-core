<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTableDataProvider implements TableDataProviderInterface
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\GuiTableDataRequestBuilderInterface
     */
    abstract protected function getGuiTableDataRequestBuilder(): GuiTableDataRequestBuilderInterface;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function createCriteria(Request $request): AbstractTransfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    abstract protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataTransfer;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfiguration
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer|mixed
     */
    public function getData(Request $request, GuiTableConfigurationTransfer $guiTableConfiguration)
    {
        $guiTableDataRequestTransfer = $this->getGuiTableDataRequestBuilder()
            ->buildGuiTableDataRequest($request, $guiTableConfiguration);
        $criteriaTransfer = $this->createCriteria($request);
        $criteriaTransfer = $this->mapFiltersToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapPagingToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapSortingToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);
        $criteriaTransfer = $this->mapLocaleToCriteria($guiTableDataRequestTransfer, $criteriaTransfer);

        return $this->fetchData($criteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
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
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapPagingToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $criteriaTransfer): AbstractTransfer
    {
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
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapSortingToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $criteriaTransfer): AbstractTransfer
    {
        $orderSetter = 'setOrderBy';
        $orderDirectionSetter = 'setOrderDirection';
        if (!method_exists($criteriaTransfer, $orderSetter) && !method_exists($criteriaTransfer, $orderDirectionSetter)) {
            throw new InvalidCriteriaPropertyException($criteriaTransfer, 'page|pageSize');
        }

        $criteriaTransfer->setOrderBy($guiTableDataRequestTransfer->getOrderBy());
        $criteriaTransfer->setOrderDirection($guiTableDataRequestTransfer->getOrderDirection());

        return $criteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapLocaleToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $criteriaTransfer): AbstractTransfer
    {
        $localeSetter = 'setIdLocale';

        if (!method_exists($criteriaTransfer, $localeSetter)) {
            return $criteriaTransfer;
        }

        $criteriaTransfer->setIdLocale($guiTableDataRequestTransfer->getIdLocale());

        return $criteriaTransfer;
    }
}
