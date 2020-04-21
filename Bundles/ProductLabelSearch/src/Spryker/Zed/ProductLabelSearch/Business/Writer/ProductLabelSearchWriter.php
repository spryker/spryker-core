<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business\Writer;

use Spryker\Shared\ProductLabelSearch\ProductLabelSearchConfig;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface;
use Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface;

class ProductLabelSearchWriter implements ProductLabelSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'spy_product_label_product_abstract.fk_product_abstract';

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
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelEvents(array $eventTransfers): void
    {
        $productLabelIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $productAbstractIds = $this->productLabelSearchRepository
            ->getProductAbstractIdsByProductLabelIds($productLabelIds);
        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductLabelProductAbstractEvents(array $eventTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_FK_PRODUCT_ABSTRACT
        );

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function writeCollection(array $productAbstractIds): void
    {
        $this->productPageSearchFacade->refresh($productAbstractIds, [ProductLabelSearchConfig::PLUGIN_PRODUCT_LABEL_DATA]);
    }
}
