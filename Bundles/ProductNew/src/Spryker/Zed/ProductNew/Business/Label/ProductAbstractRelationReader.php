<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Business\Label;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductNew\Business\Exception\ProductLabelNewNotFoundException;
use Spryker\Zed\ProductNew\Persistence\ProductNewQueryContainerInterface;
use Spryker\Zed\ProductNew\ProductNewConfig;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductNew\Persistence\ProductNewQueryContainerInterface
     */
    protected $productNewQueryContainer;

    /**
     * @var \Spryker\Zed\ProductNew\ProductNewConfig
     */
    protected $productNewConfig;

    /**
     * @param \Spryker\Zed\ProductNew\Persistence\ProductNewQueryContainerInterface $productNewQueryContainer
     * @param \Spryker\Zed\ProductNew\ProductNewConfig $productNewConfig
     */
    public function __construct(ProductNewQueryContainerInterface $productNewQueryContainer, ProductNewConfig $productNewConfig)
    {
        $this->productNewQueryContainer = $productNewQueryContainer;
        $this->productNewConfig = $productNewConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges()
    {
        $result = [];

        $productLabelNewEntity = $this->getProductLabelNewEntity();

        if (!$productLabelNewEntity->getIsActive()) {
            return [];
        }

        $relationsToDeAssign = $this->findRelationsBecomingInactive($productLabelNewEntity);
        $relationsToAssign = $this->findRelationsBecomingActive($productLabelNewEntity);

        $idProductLabels = array_merge(array_keys($relationsToDeAssign), array_keys($relationsToAssign));
        foreach ($idProductLabels as $idProductLabel) {
            $result[] = $this->mapRelationTransfer($idProductLabel, $relationsToAssign, $relationsToDeAssign);
        }

        return $result;
    }

    /**
     * @throws \Spryker\Zed\ProductNew\Business\Exception\ProductLabelNewNotFoundException
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    protected function getProductLabelNewEntity()
    {
        $labelNewName = $this->productNewConfig->getLabelNewName();
        $productLabelNewEntity = $this->productNewQueryContainer
            ->queryProductLabelByName($labelNewName)
            ->findOne();

        if (!$productLabelNewEntity) {
            throw new ProductLabelNewNotFoundException(sprintf(
                'Product Label "%1$s" doesn\'t exists. You can fix this problem by persisting a new Product Label entity into your database with "%1$s" name.',
                $labelNewName
            ));
        }

        return $productLabelNewEntity;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return array
     */
    protected function findRelationsBecomingInactive(SpyProductLabel $productLabelEntity)
    {
        $relations = [];

        $productLabelProductAbstractEntities = $this->productNewQueryContainer
            ->queryRelationsBecomingInactive($productLabelEntity->getIdProductLabel())
            ->find();

        foreach ($productLabelProductAbstractEntities as $productLabelProductAbstractEntity) {
            $relations[$productLabelEntity->getIdProductLabel()][] = $productLabelProductAbstractEntity->getFkProductAbstract();
        }

        return $relations;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return array
     */
    protected function findRelationsBecomingActive(SpyProductLabel $productLabelEntity)
    {
        $relations = [];

        $productAbstractEntities = $this->productNewQueryContainer
            ->queryRelationsBecomingActive($productLabelEntity->getIdProductLabel())
            ->find();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $relations[$productLabelEntity->getIdProductLabel()][] = $productAbstractEntity->getIdProductAbstract();
        }

        return $relations;
    }

    /**
     * @param int $idProductLabel
     * @param array $relationsToAssign
     * @param array $relationsToDeAssign
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer
     */
    protected function mapRelationTransfer($idProductLabel, array $relationsToAssign, array $relationsToDeAssign)
    {
        $productLabelProductAbstractRelationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $productLabelProductAbstractRelationsTransfer->setIdProductLabel($idProductLabel);

        if (!empty($relationsToAssign[$idProductLabel])) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToAssign($relationsToAssign[$idProductLabel]);
        }

        if (!empty($relationsToDeAssign[$idProductLabel])) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToDeAssign($relationsToDeAssign[$idProductLabel]);
        }

        return $productLabelProductAbstractRelationsTransfer;
    }
}
