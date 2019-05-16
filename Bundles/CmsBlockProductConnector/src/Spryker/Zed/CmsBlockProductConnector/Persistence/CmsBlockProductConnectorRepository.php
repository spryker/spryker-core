<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorPersistenceFactory getFactory()
 */
class CmsBlockProductConnectorRepository extends AbstractRepository implements CmsBlockProductConnectorRepositoryInterface
{
    /**
     * @uses \Orm\Zed\CmsBlockProductConnector\Persistence\Map\SpyCmsBlockProductConnectorTableMap
     */
    protected const FK_PRODUCT_ABSTRACT = 'fkProductAbstract';

    /***
     * @param int $idLocale
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAssignedProductAbstracts(int $idLocale, int $idCmsBlock): array
    {
        $productAbstractTransfers = [];
        $productAbstractEntities = $this->getFactory()->getCmsBlockProductConnectorToProductAbstractQueryContainer()
            ->queryProductAbstractWithName($idLocale)
            ->useSpyCmsBlockProductConnectorQuery()
                ->filterByFkCmsBlock($idCmsBlock)
            ->endUse()
            ->find();

        $factory = $this->getFactory();

        /**
         * @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
         */
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $factory->createCmsBlockProductConnectorMapper()
                ->mapProductAbstractEntityToProductAbstractTransfer($productAbstractEntity, new ProductAbstractTransfer());
        }

        return $productAbstractTransfers;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return int[]
     */
    public function getAssignedProductAbstractIds(int $idCmsBlock): array
    {
        return $this->getFactory()->createCmsBlockProductConnectorQuery()
            ->filterByFkCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues(static::FK_PRODUCT_ABSTRACT);
    }
}
