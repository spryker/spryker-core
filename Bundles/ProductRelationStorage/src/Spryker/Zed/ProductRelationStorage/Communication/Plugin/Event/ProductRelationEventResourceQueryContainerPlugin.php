<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event;

use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelationStorage\Communication\ProductRelationStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 */
class ProductRelationEventResourceQueryContainerPlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData(array $ids = []): ?ModelCriteria
    {
        $query = $this->getQueryContainer()->queryProductRelationsByIds($ids);

        if ($ids === []) {
            $query->clear();
        }

        return $query->orderBy($this->getIdColumnName());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT;
    }
}
