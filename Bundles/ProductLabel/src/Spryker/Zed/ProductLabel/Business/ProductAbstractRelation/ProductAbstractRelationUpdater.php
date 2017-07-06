<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductAbstractRelationUpdater implements ProductAbstractRelationUpdaterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationDeleterInterface
     */
    protected $productAbstractRelationDeleter;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationWriterInterface
     */
    protected $productAbstractRelationWriter;

    /**
     * @var array|\Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface[]
     */
    protected $productLabelRelationUpdaterPlugins;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationDeleterInterface $productAbstractRelationDeleter
     * @param \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationWriterInterface $productAbstractRelationWriter
     * @param \Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface[] $productLabelRelationUpdaterPlugins
     */
    public function __construct(
        ProductAbstractRelationDeleterInterface $productAbstractRelationDeleter,
        ProductAbstractRelationWriterInterface $productAbstractRelationWriter,
        array $productLabelRelationUpdaterPlugins
    ) {
        $this->productAbstractRelationDeleter = $productAbstractRelationDeleter;
        $this->productAbstractRelationWriter = $productAbstractRelationWriter;
        $this->productLabelRelationUpdaterPlugins = $productLabelRelationUpdaterPlugins;
    }

    /**
     * @return void
     */
    public function updateProductLabelRelations()
    {
        $this->handleDatabaseTransaction(function () {
            $this->executeUpdateProductLabelRelationsTransaction();
        });
    }

    /**
     * @return void
     */
    protected function executeUpdateProductLabelRelationsTransaction()
    {
        foreach ($this->productLabelRelationUpdaterPlugins as $productLabelRelationUpdaterPlugin) {
            $productLabelProductAbstractRelationTransfers = $productLabelRelationUpdaterPlugin->findProductLabelProductAbstractRelationChanges();

            foreach ($productLabelProductAbstractRelationTransfers as $productLabelProductAbstractRelationTransfer) {
                $this->productAbstractRelationDeleter->removeRelations(
                    $productLabelProductAbstractRelationTransfer->getIdProductLabel(),
                    $productLabelProductAbstractRelationTransfer->getIdsProductAbstractToDeAssign()
                );

                $this->productAbstractRelationWriter->addRelations(
                    $productLabelProductAbstractRelationTransfer->getIdProductLabel(),
                    $productLabelProductAbstractRelationTransfer->getIdsProductAbstractToAssign()
                );
            }
        }
    }

}
