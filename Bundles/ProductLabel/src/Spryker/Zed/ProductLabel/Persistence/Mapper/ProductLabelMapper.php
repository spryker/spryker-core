<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
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
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelProductAbstractMapper
     */
    protected $productLabelProductAbstractMapper;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper $productLabelStoreRelationMapper
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelLocalizedAttributesMapper $productLabelLocalizedAttributesMapper
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelProductAbstractMapper $productLabelProductAbstractMapper
     */
    public function __construct(
        ProductLabelStoreRelationMapper $productLabelStoreRelationMapper,
        ProductLabelLocalizedAttributesMapper $productLabelLocalizedAttributesMapper,
        ProductLabelProductAbstractMapper $productLabelProductAbstractMapper
    ) {
        $this->productLabelStoreRelationMapper = $productLabelStoreRelationMapper;
        $this->productLabelLocalizedAttributesMapper = $productLabelLocalizedAttributesMapper;
        $this->productLabelProductAbstractMapper = $productLabelProductAbstractMapper;
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

        $productLabelProductAbstractTransfers = $this->productLabelProductAbstractMapper
            ->mapProductLabelProductAbstractEntitiesToProductLabelProductTransfers(
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
}
