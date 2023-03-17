<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SpyProductLabelEntityTransfer;
use Generated\Shared\Transfer\SpyProductLabelProductAbstractEntityTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductLabel\Persistence\Base\SpyProductLabelProductAbstract;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelMapper
{
    /**
     * @var string
     */
    protected const VALIDITY_DATE_FORMAT = 'Y-m-d';

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper
     */
    protected $productLabelStoreRelationMapper;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelLocalizedAttributesMapper
     */
    protected $productLabelLocalizedAttributesMapper;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper $productLabelStoreRelationMapper
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelLocalizedAttributesMapper $productLabelLocalizedAttributesMapper
     */
    public function __construct(
        ProductLabelStoreRelationMapper $productLabelStoreRelationMapper,
        ProductLabelLocalizedAttributesMapper $productLabelLocalizedAttributesMapper
    ) {
        $this->productLabelStoreRelationMapper = $productLabelStoreRelationMapper;
        $this->productLabelLocalizedAttributesMapper = $productLabelLocalizedAttributesMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntities
     * @param array<\Generated\Shared\Transfer\ProductLabelTransfer> $productLabelTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function mapProductLabelEntitiesToProductLabelTransfers(
        ObjectCollection $productLabelEntities,
        array $productLabelTransfers
    ): array {
        foreach ($productLabelEntities as $productLabelEntity) {
            $productLabelTransfers[] = $this->mapProductLabelEntityToProductLabelTransfer(
                $productLabelEntity,
                new ProductLabelTransfer(),
            );
        }

        return $productLabelTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        $productLabelTransfer->setValidFrom(
            $productLabelEntity->getValidFrom(static::VALIDITY_DATE_FORMAT),
        );
        $productLabelTransfer->setValidTo(
            $productLabelEntity->getValidTo(static::VALIDITY_DATE_FORMAT),
        );

        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity($productLabelEntity->getIdProductLabel());

        if ($productLabelEntity->getProductLabelStores()->count()) {
            $storeRelationTransfer = $this->productLabelStoreRelationMapper->mapProductLabelStoreEntitiesToStoreRelationTransfer(
                $productLabelEntity->getProductLabelStores(),
                $storeRelationTransfer,
            );
        }
        $productLabelTransfer->setStoreRelation($storeRelationTransfer);

        $productLabelEntity->initSpyProductLabelLocalizedAttributess(false);

        $productLabelLocalizedAttributesTransfers = $this->productLabelLocalizedAttributesMapper
            ->mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
                $productLabelEntity->getSpyProductLabelLocalizedAttributess(),
                $productLabelTransfer->getLocalizedAttributesCollection(),
            );
        $productLabelTransfer->setLocalizedAttributesCollection(
            new ArrayObject($productLabelLocalizedAttributesTransfers),
        );

        $productLabelProductAbstractTransfers = $this->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers(
            $productLabelEntity->getSpyProductLabelProductAbstracts(),
            [],
        );
        $productLabelTransfer->setProductLabelProductAbstracts(
            new ArrayObject($productLabelProductAbstractTransfers),
        );

        return $productLabelTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function mapProductLabelEntityTransferToProductLabelTransfer(
        SpyProductLabelEntityTransfer $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        $productLabelTransfer->setValidFrom(
            $productLabelEntity->getValidFrom(),
        );
        $productLabelTransfer->setValidTo(
            $productLabelEntity->getValidTo(),
        );

        $productLabelLocalizedAttributesTransfers = $this->productLabelLocalizedAttributesMapper
            ->mapProductLabelLocalizedAttributesEntityTransfersToProductLabelLocalizedAttributesTransfers(
                $productLabelEntity->getSpyProductLabelLocalizedAttributess(),
                $productLabelTransfer->getLocalizedAttributesCollection(),
            );
        $productLabelTransfer->setLocalizedAttributesCollection(
            new ArrayObject($productLabelLocalizedAttributesTransfers),
        );

        $productLabelProductAbstractTransfers = $this->mapProductLabelProductAbstractEntityTransfersToProductLabelProductTransfers(
            $productLabelEntity->getSpyProductLabelProductAbstracts(),
            [],
        );
        $productLabelTransfer->setProductLabelProductAbstracts(
            new ArrayObject($productLabelProductAbstractTransfers),
        );

        return $productLabelTransfer;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        return $productLabelLocalizedAttributesTransfer
            ->fromArray($productLabelLocalizedAttributesEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel
     */
    public function mapProductLabelTransferToProductLabelEntity(
        ProductLabelTransfer $productLabelTransfer,
        SpyProductLabel $productLabelEntity
    ): SpyProductLabel {
        $productLabelEntity->fromArray($productLabelTransfer->toArray());

        return $productLabelEntity;
    }

    /**
     * @param array<int, \Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntities
     * @param \Generated\Shared\Transfer\ProductLabelCollectionTransfer $productLabelCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    public function mapProductLabelEntitiesToProductLabelCollectionTransfer(
        array $productLabelEntities,
        ProductLabelCollectionTransfer $productLabelCollectionTransfer
    ): ProductLabelCollectionTransfer {
        foreach ($productLabelEntities as $productLabelEntity) {
            $productLabelCollectionTransfer->addProductLabel(
                $this->mapProductLabelEntityToProductLabelTransfer($productLabelEntity, new ProductLabelTransfer()),
            );
        }

        return $productLabelCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstract> $productLabelProductAbstractEntities
     * @param array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer> $productLabelProductAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers(
        ObjectCollection $productLabelProductAbstractEntities,
        array $productLabelProductAbstractTransfers
    ): array {
        foreach ($productLabelProductAbstractEntities as $productLabelProductAbstractEntity) {
            $productLabelProductAbstractTransfers[] = $this->mapProductLabelProductAbstractEntityToProductLabelProductTransfer(
                $productLabelProductAbstractEntity,
                new ProductLabelProductAbstractTransfer(),
            );
        }

        return $productLabelProductAbstractTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SpyProductLabelProductAbstractEntityTransfer> $productLabelProductAbstractEntityTransfers
     * @param array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer> $productLabelProductAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function mapProductLabelProductAbstractEntityTransfersToProductLabelProductTransfers(
        ArrayObject $productLabelProductAbstractEntityTransfers,
        array $productLabelProductAbstractTransfers
    ): array {
        foreach ($productLabelProductAbstractEntityTransfers as $productLabelProductAbstractEntityTransfer) {
            $productLabelProductAbstractTransfers[] = $this->mapProductLabelProductAbstractEntityTransferToProductLabelProductTransfer(
                $productLabelProductAbstractEntityTransfer,
                new ProductLabelProductAbstractTransfer(),
            );
        }

        return $productLabelProductAbstractTransfers;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\SpyProductLabelProductAbstractEntityTransfer> $productLabelProductAbstractEntityTransfers
     * @param array $productLabelProductAbstractTransfers
     *
     * @return array
     */
    public function mapProductLabelProductAbstractEntitiesToProductLabelProductTransfersWithProductLabelData(
        array $productLabelProductAbstractEntityTransfers,
        array $productLabelProductAbstractTransfers
    ): array {
        foreach ($productLabelProductAbstractEntityTransfers as $productLabelProductAbstractEntityTransfer) {
            $productLabelProductAbstractTransfer = $this->mapProductLabelProductAbstractEntityTransferToProductLabelProductTransfer(
                $productLabelProductAbstractEntityTransfer,
                new ProductLabelProductAbstractTransfer(),
            );

            $productLabelTransfer = $this->mapProductLabelEntityTransferToProductLabelTransfer(
                $productLabelProductAbstractEntityTransfer->getSpyProductLabel(),
                new ProductLabelTransfer(),
            );

            $productLabelProductAbstractTransfer->setProductLabel($productLabelTransfer);

            $productLabelProductAbstractTransfers[] = $productLabelProductAbstractTransfer;
        }

        return $productLabelProductAbstractTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\Base\SpyProductLabelProductAbstract $productLabelProductAbstractEntity
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer
     */
    protected function mapProductLabelProductAbstractEntityToProductLabelProductTransfer(
        SpyProductLabelProductAbstract $productLabelProductAbstractEntity,
        ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
    ): ProductLabelProductAbstractTransfer {
        return $productLabelProductAbstractTransfer->fromArray($productLabelProductAbstractEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelProductAbstractEntityTransfer $productLabelProductAbstractEntityTransfer
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer
     */
    protected function mapProductLabelProductAbstractEntityTransferToProductLabelProductTransfer(
        SpyProductLabelProductAbstractEntityTransfer $productLabelProductAbstractEntityTransfer,
        ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer
    ): ProductLabelProductAbstractTransfer {
        return $productLabelProductAbstractTransfer->fromArray($productLabelProductAbstractEntityTransfer->toArray(), true);
    }
}
