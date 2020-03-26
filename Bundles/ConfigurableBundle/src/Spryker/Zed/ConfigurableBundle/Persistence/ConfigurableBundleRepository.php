<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundlePersistenceFactory getFactory()
 */
class ConfigurableBundleRepository extends AbstractRepository implements ConfigurableBundleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer {
        $configurableBundleTemplateQuery = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery();

        $configurableBundleTemplateQuery = $this->setConfigurableBundleTemplateFilters(
            $configurableBundleTemplateQuery,
            $configurableBundleTemplateFilterTransfer
        );

        $configurableBundleTemplateEntity = $configurableBundleTemplateQuery->findOne();

        if (!$configurableBundleTemplateEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateEntityToTemplateTransfer($configurableBundleTemplateEntity, new ConfigurableBundleTemplateTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateCollectionTransfer {
        $configurableBundleTemplateQuery = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery();

        $configurableBundleTemplateQuery = $this->setConfigurableBundleTemplateFilters(
            $configurableBundleTemplateQuery,
            $configurableBundleTemplateFilterTransfer
        );

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateEntityCollectionToTemplateTransferCollection($configurableBundleTemplateQuery->find());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotQuery = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->joinWithSpyConfigurableBundleTemplate();

        $configurableBundleTemplateSlotQuery = $this->setConfigurableBundleTemplateSlotFilters(
            $configurableBundleTemplateSlotQuery,
            $configurableBundleTemplateSlotFilterTransfer
        );

        $configurableBundleTemplateSlotEntity = $configurableBundleTemplateSlotQuery->findOne();

        if (!$configurableBundleTemplateSlotEntity) {
            return null;
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateSlotEntityToTemplateSlotTransfer($configurableBundleTemplateSlotEntity, new ConfigurableBundleTemplateSlotTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer
     */
    public function getConfigurableBundleTemplateSlotCollection(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotCollectionTransfer {
        $configurableBundleTemplateSlotQuery = $this->getFactory()
            ->getConfigurableBundleTemplateSlotPropelQuery()
            ->joinWithSpyConfigurableBundleTemplate()
            ->orderByIdConfigurableBundleTemplateSlot(Criteria::ASC);

        $configurableBundleTemplateSlotQuery = $this->setConfigurableBundleTemplateSlotFilters(
            $configurableBundleTemplateSlotQuery,
            $configurableBundleTemplateSlotFilterTransfer
        );

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapTemplateSlotEntityCollectionToTemplateSlotTransferCollection($configurableBundleTemplateSlotQuery->find());
    }

    /**
     * @param string[] $templateUuids
     *
     * @return string[]
     */
    public function getActiveConfigurableBundleTemplateUuids(array $templateUuids): array
    {
        if (!$templateUuids) {
            return [];
        }

        return $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->filterByUuid_In($templateUuids)
            ->filterByIsActive(true)
            ->select([SpyConfigurableBundleTemplateTableMap::COL_UUID])
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function setConfigurableBundleTemplateFilters(
        SpyConfigurableBundleTemplateQuery $configurableBundleTemplateQuery,
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): SpyConfigurableBundleTemplateQuery {
        if ($configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()) {
            $configurableBundleTemplateQuery->filterByIdConfigurableBundleTemplate(
                $configurableBundleTemplateFilterTransfer->getIdConfigurableBundleTemplate()
            );
        }

        if ($configurableBundleTemplateFilterTransfer->getConfigurableBundleTemplateIds()) {
            $configurableBundleTemplateQuery->filterByIdConfigurableBundleTemplate_In(
                $configurableBundleTemplateFilterTransfer->getConfigurableBundleTemplateIds()
            );
        }

        if ($configurableBundleTemplateFilterTransfer->getFilter()) {
            $configurableBundleTemplateQuery = $this->buildQueryFromCriteria(
                $configurableBundleTemplateQuery,
                $configurableBundleTemplateFilterTransfer->getFilter()
            );

            $configurableBundleTemplateQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        }

        return $configurableBundleTemplateQuery;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function setConfigurableBundleTemplateSlotFilters(
        SpyConfigurableBundleTemplateSlotQuery $configurableBundleTemplateSlotQuery,
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): SpyConfigurableBundleTemplateSlotQuery {
        if (
            $configurableBundleTemplateSlotFilterTransfer->getProductList()
            && $configurableBundleTemplateSlotFilterTransfer->getProductList()->getIdProductList()
        ) {
            $configurableBundleTemplateSlotQuery->filterByFkProductList(
                $configurableBundleTemplateSlotFilterTransfer->getProductList()->getIdProductList()
            );
        }

        if ($configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplate()) {
            $configurableBundleTemplateSlotQuery->filterByFkConfigurableBundleTemplate(
                $configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplate()
            );
        }

        if ($configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplateSlot()) {
            $configurableBundleTemplateSlotQuery->filterByIdConfigurableBundleTemplateSlot(
                $configurableBundleTemplateSlotFilterTransfer->getIdConfigurableBundleTemplateSlot()
            );
        }

        return $configurableBundleTemplateSlotQuery;
    }

    /**
     * @module ProductImage
     * @module Locale
     *
     * @param int $idConfigurableBundleTemplate
     * @param int[] $localeIds
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getConfigurableBundleTemplateImageSets(int $idConfigurableBundleTemplate, array $localeIds): array
    {
        $productImageSetQuery = $this->getFactory()
            ->getProductImageSetQuery()
            ->filterByFkResourceConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->leftJoinWithSpyLocale()
            ->joinWithSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
                ->joinWithSpyProductImage()
                ->orderBySortOrder(Criteria::ASC)
                ->orderByIdProductImageSetToProductImage(Criteria::ASC)
            ->endUse();

        if ($localeIds) {
            $productImageSetQuery
                ->filterByFkLocale(null, Criteria::ISNULL)
                ->_or()
                ->filterByFkLocale_In($localeIds);
        }

        return $this->getFactory()
            ->createConfigurableBundleMapper()
            ->mapProductImageSetEntityCollectionToProductImageSetTransfers($productImageSetQuery->find());
    }
}
