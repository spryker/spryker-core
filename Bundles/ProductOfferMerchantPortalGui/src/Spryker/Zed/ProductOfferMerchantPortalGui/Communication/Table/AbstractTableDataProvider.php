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
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTableDataProvider implements TableDataProviderInterface
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface
     */
    abstract protected function getRequestToGuiTableDataRequestHydrator(): RequestToGuiTableDataRequestHydratorInterface;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function createPersistenceCriteria(Request $request): AbstractTransfer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $persistenceCriteria
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    abstract protected function fetchData(AbstractTransfer $persistenceCriteria): GuiTableDataTransfer;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfiguration
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer|mixed
     */
    public function getData(Request $request, GuiTableConfigurationTransfer $guiTableConfiguration)
    {
        $guiTableDataRequest = $this->getRequestToGuiTableDataRequestHydrator()->hydrate($request, $guiTableConfiguration);
        $criteria = $this->createPersistenceCriteria($request);
        $criteria = $this->mapFiltersToCriteria($guiTableDataRequest, $criteria);
        $criteria = $this->mapPagingToCriteria($guiTableDataRequest, $criteria);
        $criteria = $this->mapSortingToCriteria($guiTableDataRequest, $criteria);
        $criteria = $this->mapLocaleToCriteria($guiTableDataRequest, $criteria);

        return $this->fetchData($criteria);
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $persistenceCriteria
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapFiltersToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $persistenceCriteria): AbstractTransfer
    {
        foreach ($guiTableDataRequestTransfer->getFilters() as $filterName => $filterValue) {
            $setter = 'setFilter' . $filterName;

            if (!method_exists($persistenceCriteria, $setter)) {
                throw new InvalidCriteriaPropertyException($persistenceCriteria, $filterName);
            }

            $persistenceCriteria->$setter($filterValue);
        }

        return $persistenceCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $persistenceCriteria
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapPagingToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $persistenceCriteria): AbstractTransfer
    {
        $pageSetter = 'setPage';
        $pageSizeSetter = 'setPageSize';
        if (!method_exists($persistenceCriteria, $pageSizeSetter) && !method_exists($persistenceCriteria, $pageSetter)) {
            throw new InvalidCriteriaPropertyException($persistenceCriteria, 'page|pageSize');
        }

        $persistenceCriteria->$pageSetter($guiTableDataRequestTransfer->getPage());
        $persistenceCriteria->$pageSizeSetter($guiTableDataRequestTransfer->getPageSize());

        return $persistenceCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $persistenceCriteria
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Exception\InvalidCriteriaPropertyException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapSortingToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $persistenceCriteria): AbstractTransfer
    {
        $orderSetter = 'setOrderBy';
        $orderDirectionSetter = 'setOrderDirection';
        if (!method_exists($persistenceCriteria, $orderSetter) && !method_exists($persistenceCriteria, $orderDirectionSetter)) {
            throw new InvalidCriteriaPropertyException($persistenceCriteria, 'page|pageSize');
        }

        $persistenceCriteria->$orderSetter($guiTableDataRequestTransfer->getOrderBy());
        $persistenceCriteria->$orderDirectionSetter($guiTableDataRequestTransfer->getOrderDirection());

        return $persistenceCriteria;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $persistenceCriteria
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function mapLocaleToCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer, AbstractTransfer $persistenceCriteria): AbstractTransfer
    {
        $localeSetter = 'setLocale';

        if (!method_exists($persistenceCriteria, $localeSetter)) {
            return $persistenceCriteria;
        }

        $persistenceCriteria->setLocale($guiTableDataRequestTransfer->getLocale());

        return $persistenceCriteria;
    }
}
