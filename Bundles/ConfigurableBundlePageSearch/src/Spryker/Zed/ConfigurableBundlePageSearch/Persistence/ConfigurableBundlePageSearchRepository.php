<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Generated\Shared\Transfer\ConfigurableBundlePageSearchFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer;
use Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundlePageSearchQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchPersistenceFactory getFactory()
 */
class ConfigurableBundlePageSearchRepository extends AbstractRepository implements ConfigurableBundlePageSearchRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundlePageSearchTransfer[]
     */
    public function getConfigurableBundlePageSearchCollection(ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer): array
    {
        $configurableBundlePageSearchQuery = $this->getFactory()->createConfigurableBundlePageSearchQuery();
        $configurableBundlePageSearchQuery = $this->setConfigurableBundlePageSearchFilters(
            $configurableBundlePageSearchQuery,
            $configurableBundlePageSearchFilterTransfer
        );

        $configurableBundlePageSearchEntities = $configurableBundlePageSearchQuery->find();

        if (!$configurableBundlePageSearchEntities->count()) {
            return [];
        }

        $configurableBundlePageSearchTransfers = [];

        foreach ($configurableBundlePageSearchEntities as $configurableBundlePageSearchEntity) {
            $configurableBundlePageSearchTransfers[] = (new ConfigurableBundlePageSearchTransfer())
                ->fromArray($configurableBundlePageSearchEntity->toArray());
        }

        return $configurableBundlePageSearchTransfers;
    }

    /**
     * @param \Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundlePageSearchQuery $configurableBundlePageSearchQuery
     * @param \Generated\Shared\Transfer\ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer
     *
     * @return \Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundlePageSearchQuery
     */
    protected function setConfigurableBundlePageSearchFilters(
        SpyConfigurableBundlePageSearchQuery $configurableBundlePageSearchQuery,
        ConfigurableBundlePageSearchFilterTransfer $configurableBundlePageSearchFilterTransfer
    ): SpyConfigurableBundlePageSearchQuery {
        if ($configurableBundlePageSearchFilterTransfer->getConfigurableBundleTemplateIds()) {
            $configurableBundlePageSearchQuery->filterByFkConfigurableBundleTemplate_In(
                $configurableBundlePageSearchFilterTransfer->getConfigurableBundleTemplateIds()
            );
        }

        return $configurableBundlePageSearchQuery;
    }
}
