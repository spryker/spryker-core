<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface;
use Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface;

class ProductLabelSearchWriter implements ProductLabelSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT = 'spy_product_label_product_abstract.fk_product_abstract';

    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelStoreTableMap::COL_FK_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL = 'spy_product_label_store.fk_product_label';

    /**
     * @var \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface
     */
    protected $productPageSearchFacade;

    /**
     * @var \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface
     */
    protected $productLabelSearchRepository;

    /**
     * @param \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface $productPageSearchFacade
     * @param \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface $productLabelSearchRepository
     */
    public function __construct(
        ProductLabelSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductLabelSearchToProductPageSearchInterface $productPageSearchFacade,
        ProductLabelSearchRepositoryInterface $productLabelSearchRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productPageSearchFacade = $productPageSearchFacade;
        $this->productLabelSearchRepository = $productLabelSearchRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventEntityTransfers): void
    {
        $hydrateEventsResponseTransfer = $this->eventBehaviorFacade->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers)),
        );
        $productAbstractIdTimestampMap = $this->productLabelSearchRepository
            ->getProductAbstractIdTimestampMap($hydrateEventsResponseTransfer->getIdTimestampMap());

        $this->writeCollection($productAbstractIdTimestampMap);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelProductAbstractEvents(array $eventEntityTransfers): void
    {
        $hydrateEventsResponseTransfer = $this->eventBehaviorFacade->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers))
                ->setForeignKeyName(static::COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT),
        );

        $this->writeCollection($hydrateEventsResponseTransfer->getForeignKeyTimestampMap());
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelStoreEvents(array $eventEntityTransfers): void
    {
        $hydrateEventsResponseTransfer = $this->eventBehaviorFacade->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers))
                ->setForeignKeyName(static::COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL),
        );

        $productAbstractIdTimestampMap = $this->productLabelSearchRepository
            ->getProductAbstractIdTimestampMap($hydrateEventsResponseTransfer->getForeignKeyTimestampMap());

        $this->writeCollection($productAbstractIdTimestampMap);
    }

    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    protected function writeCollection(array $productAbstractIdTimestampMap): void
    {
        if (!$productAbstractIdTimestampMap) {
            return;
        }

        $this->productPageSearchFacade->publishWithTimestamp($productAbstractIdTimestampMap);
    }
}
