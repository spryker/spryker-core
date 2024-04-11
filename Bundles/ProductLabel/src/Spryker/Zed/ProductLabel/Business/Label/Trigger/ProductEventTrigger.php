<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\Trigger;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductLabelProductAbstractTransfer;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface;

class ProductEventTrigger implements ProductEventTriggerInterface
{
    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_ABSTRACT_UPDATE
     *
     * @var string
     */
    protected const PRODUCT_ABSTRACT_UPDATE = 'Product.product_abstract.update';

    /**
     * @var string
     */
    protected const KEY_FK_PRODUCT_LABEL = 'fk_product_label';

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface
     */
    protected ProductLabelRepositoryInterface $productLabelRepository;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToEventInterface $eventFacade
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface $productLabelRepository
     */
    public function __construct(
        ProductLabelToEventInterface $eventFacade,
        ProductLabelRepositoryInterface $productLabelRepository
    ) {
        $this->eventFacade = $eventFacade;
        $this->productLabelRepository = $productLabelRepository;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function triggerProductUpdateEvents(array $productAbstractIds): void
    {
        $eventEntityTransfers = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $eventEntityTransfers[] = (new EventEntityTransfer())
                ->setId($idProductAbstract);
        }

        $this->eventFacade->triggerBulk(static::PRODUCT_ABSTRACT_UPDATE, $eventEntityTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function triggerProductAbstractUpdateEvents(array $eventEntityTransfers): void
    {
        $productLabelsIds = $this->extractProductLabelIds($eventEntityTransfers);
        if (!$productLabelsIds) {
            return;
        }

        $productLabelProductAbstractTransfers = $this->productLabelRepository->getProductAbstractRelationsByIdProductLabelIds($productLabelsIds);
        $productAbstractIds = array_map(
            fn (ProductLabelProductAbstractTransfer $productLabelProductAbstractTransfer) => $productLabelProductAbstractTransfer->getFkProductAbstract(),
            $productLabelProductAbstractTransfers,
        );

        $this->triggerProductUpdateEvents(array_unique($productAbstractIds));
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return array<int>
     */
    protected function extractProductLabelIds(array $eventEntityTransfers): array
    {
        $productLabelsIds = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            // checking if event has foreign key for product label, the format is {table_name}.fk_product_label
            $foreignKeys = $eventEntityTransfer->getForeignKeys();
            $key = sprintf('%s.%s', $eventEntityTransfer->getName(), static::KEY_FK_PRODUCT_LABEL);
            if (!empty($foreignKeys[$key])) {
                $productLabelsIds[$foreignKeys[$key]] = $foreignKeys[$key];

                continue;
            }

            if ($eventEntityTransfer->getId() !== null) {
                $productLabelsIds[$eventEntityTransfer->getId()] = $eventEntityTransfer->getId();
            }
        }

        return array_values($productLabelsIds);
    }
}
