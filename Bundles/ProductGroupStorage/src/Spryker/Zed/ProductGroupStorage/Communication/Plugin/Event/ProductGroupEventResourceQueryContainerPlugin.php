<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductGroupStorage\ProductGroupStorageConfig getConfig()
 */
class ProductGroupEventResourceQueryContainerPlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductGroupStorageConstants::PRODUCT_GROUP_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData(array $ids = []): ?ModelCriteria
    {
        $query = $this->getQueryContainer()->queryProductAbstractGroupByGroupIds($ids);

        if ($ids === []) {
            $query->clear();
        }

        return $query->orderBy($this->getIdColumnName());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductGroupEvents::PRODUCT_GROUP_PUBLISH;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT;
    }
}
