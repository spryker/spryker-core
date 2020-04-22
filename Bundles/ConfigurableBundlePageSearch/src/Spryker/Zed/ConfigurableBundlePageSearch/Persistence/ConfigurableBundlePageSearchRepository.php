<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundleTemplatePageSearchQuery;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchPersistenceFactory getFactory()
 */
class ConfigurableBundlePageSearchRepository extends AbstractRepository implements ConfigurableBundlePageSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer
     */
    public function getConfigurableBundleTemplatePageSearchCollection(
        ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
    ): ConfigurableBundleTemplatePageSearchCollectionTransfer {
        $configurableBundleTemplatePageSearchQuery = $this->getFactory()->getConfigurableBundlePageSearchQuery();
        $configurableBundleTemplatePageSearchQuery = $this->setConfigurableBundlePageSearchFilters(
            $configurableBundleTemplatePageSearchQuery,
            $configurableBundleTemplatePageSearchFilterTransfer
        );

        $configurableBundleTemplatePageSearchEntities = $configurableBundleTemplatePageSearchQuery->find();

        $configurableBundleTemplatePageSearchCollectionTransfer = new ConfigurableBundleTemplatePageSearchCollectionTransfer();

        if (!$configurableBundleTemplatePageSearchEntities->count()) {
            return $configurableBundleTemplatePageSearchCollectionTransfer;
        }

        foreach ($configurableBundleTemplatePageSearchEntities as $configurableBundleTemplatePageSearchEntity) {
            $configurableBundleTemplatePageSearchCollectionTransfer->addConfigurableBundleTemplatePageSearch(
                (new ConfigurableBundleTemplatePageSearchTransfer())->fromArray($configurableBundleTemplatePageSearchEntity->toArray(), true)
                    ->setType(ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_TEMPLATE_RESOURCE_NAME)
            );
        }

        return $configurableBundleTemplatePageSearchCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(FilterTransfer $filterTransfer): ConfigurableBundleTemplateCollectionTransfer
    {
        $configurableBundleTemplatePropelQuery = $this->getFactory()
            ->getConfigurableBundleTemplatePropelQuery()
            ->select([SpyConfigurableBundleTemplateTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE]);

        $configurableBundleTemplateIds = $this->buildQueryFromCriteria($configurableBundleTemplatePropelQuery, $filterTransfer)
            ->setFormatter(SimpleArrayFormatter::class)
            ->find()
            ->toArray();

        $configurableBundleTemplateCollectionTransfer = new ConfigurableBundleTemplateCollectionTransfer();

        foreach ($configurableBundleTemplateIds as $configurableBundleTemplateId) {
            $configurableBundleTemplateCollectionTransfer->addConfigurableBundleTemplate(
                (new ConfigurableBundleTemplateTransfer())->setIdConfigurableBundleTemplate($configurableBundleTemplateId)
            );
        }

        return $configurableBundleTemplateCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundleTemplatePageSearchQuery $configurableBundleTemplatePageSearchQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundleTemplatePageSearchQuery
     */
    protected function setConfigurableBundlePageSearchFilters(
        SpyConfigurableBundleTemplatePageSearchQuery $configurableBundleTemplatePageSearchQuery,
        ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
    ): SpyConfigurableBundleTemplatePageSearchQuery {
        if ($configurableBundleTemplatePageSearchFilterTransfer->getConfigurableBundleTemplateIds()) {
            $configurableBundleTemplatePageSearchQuery->filterByFkConfigurableBundleTemplate_In(
                $configurableBundleTemplatePageSearchFilterTransfer->getConfigurableBundleTemplateIds()
            );
        }

        if ($configurableBundleTemplatePageSearchFilterTransfer->getOffset() !== null) {
            $configurableBundleTemplatePageSearchQuery->setOffset($configurableBundleTemplatePageSearchFilterTransfer->getOffset());
        }

        if ($configurableBundleTemplatePageSearchFilterTransfer->getLimit()) {
            $configurableBundleTemplatePageSearchQuery->setLimit($configurableBundleTemplatePageSearchFilterTransfer->getLimit());
        }

        return $configurableBundleTemplatePageSearchQuery;
    }
}
