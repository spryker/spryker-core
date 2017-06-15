<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductLabel\ProductLabelConfig;
use Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException;
use Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class LabelReader implements LabelReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface
     */
    protected $localizedAttributesCollectionReader;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\LocalizedAttributesCollectionReaderInterface $localizedAttributesCollectionReader
     */
    public function __construct(
        ProductLabelQueryContainerInterface $queryContainer,
        LocalizedAttributesCollectionReaderInterface $localizedAttributesCollectionReader
    ) {
        $this->queryContainer = $queryContainer;
        $this->localizedAttributesCollectionReader = $localizedAttributesCollectionReader;
    }

    /**
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getByIdProductLabel($idProductLabel)
    {
        $productLabelEntity = $this->findEntityByIdProductLabel($idProductLabel);

        if (!$productLabelEntity) {
            throw new MissingProductLabelException(
                sprintf('Could not find product label for id "%s"', $idProductLabel)
            );
        }

        $productLabelTransfer = $this->createTransferFromEntity($productLabelEntity);
        $this->addLocalizedAttributes($productLabelTransfer);

        return $productLabelTransfer;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel|null
     */
    protected function findEntityByIdProductLabel($idProductLabel)
    {
        return $this
            ->queryContainer
            ->queryProductLabelById($idProductLabel)
            ->findOne();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAll()
    {
        $productLabelEntities = $this->findAllEntitiesSortedByPosition();
        $productLabelTransferCollection = $this->createTransferCollectionForEntities($productLabelEntities);

        return $productLabelTransferCollection;
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findAllEntitiesSortedByPosition()
    {
        return $this
            ->queryContainer
            ->queryProductLabelsSortedByPosition()
            ->find();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function findAllByIdProductAbstract($idProductAbstract)
    {
        $productLabelEntities = $this->findEntitiesByIdProductAbstract($idProductAbstract);
        $productLabelTransferCollection = $this->createTransferCollectionForEntities($productLabelEntities);

        return $productLabelTransferCollection;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findEntitiesByIdProductAbstract($idProductAbstract)
    {
        return $this
            ->queryContainer
            ->queryProductsLabelByIdProductAbstract($idProductAbstract)
            ->find();
    }

    /**
     * @param array|\Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]|\Propel\Runtime\Collection\ObjectCollection $productLabelEntities
     *
     * @return array
     */
    protected function createTransferCollectionForEntities(ObjectCollection $productLabelEntities)
    {
        $productLabelTransferCollection = [];

        foreach ($productLabelEntities as $productLabelEntity) {
            $productLabelTransfer = $this->createTransferFromEntity($productLabelEntity);
            $this->addLocalizedAttributes($productLabelTransfer);

            $productLabelTransferCollection[] = $productLabelTransfer;
        }

        return $productLabelTransferCollection;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createTransferFromEntity(SpyProductLabel $productLabelEntity)
    {
        $productLabelTransfer = new ProductLabelTransfer();
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        $productLabelTransfer->setValidFrom(
            $productLabelEntity->getValidFrom(ProductLabelConfig::VALIDITY_DATE_FORMAT)
        );
        $productLabelTransfer->setValidTo(
            $productLabelEntity->getValidTo(ProductLabelConfig::VALIDITY_DATE_FORMAT)
        );

        return $productLabelTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function addLocalizedAttributes(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer->setLocalizedAttributesCollection(
            $this
                ->localizedAttributesCollectionReader
                ->findAllByIdProductLabel($productLabelTransfer->getIdProductLabel())
        );
    }

}
