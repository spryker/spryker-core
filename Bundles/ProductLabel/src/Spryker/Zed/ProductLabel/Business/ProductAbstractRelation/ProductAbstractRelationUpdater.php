<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\ProductAbstractRelation;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Psr\Log\LoggerInterface;
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
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationDeleterInterface $productAbstractRelationDeleter
     * @param \Spryker\Zed\ProductLabel\Business\ProductAbstractRelation\ProductAbstractRelationWriterInterface $productAbstractRelationWriter
     * @param \Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface[] $productLabelRelationUpdaterPlugins
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        ProductAbstractRelationDeleterInterface $productAbstractRelationDeleter,
        ProductAbstractRelationWriterInterface $productAbstractRelationWriter,
        array $productLabelRelationUpdaterPlugins,
        ?LoggerInterface $logger = null
    ) {
        $this->productAbstractRelationDeleter = $productAbstractRelationDeleter;
        $this->productAbstractRelationWriter = $productAbstractRelationWriter;
        $this->productLabelRelationUpdaterPlugins = $productLabelRelationUpdaterPlugins;
        $this->logger = $logger;
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
            $pluginName = get_class($productLabelRelationUpdaterPlugin);
            $productLabelProductAbstractRelationTransfers = $productLabelRelationUpdaterPlugin->findProductLabelProductAbstractRelationChanges();

            $this->debug(sprintf(
                '%s - Found %d labels to update.',
                $pluginName,
                count($productLabelProductAbstractRelationTransfers)
            ));

            $this->updateRelations($productLabelProductAbstractRelationTransfers, $pluginName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[] $productLabelProductAbstractRelationTransfers
     * @param string $pluginName
     *
     * @return void
     */
    protected function updateRelations($productLabelProductAbstractRelationTransfers, $pluginName)
    {
        foreach ($productLabelProductAbstractRelationTransfers as $productLabelProductAbstractRelationTransfer) {
            $this->deAssign($productLabelProductAbstractRelationTransfer, $pluginName);
            $this->assign($productLabelProductAbstractRelationTransfer, $pluginName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer $productLabelProductAbstractRelationTransfer
     * @param string $pluginName
     *
     * @return void
     */
    protected function deAssign(ProductLabelProductAbstractRelationsTransfer $productLabelProductAbstractRelationTransfer, $pluginName)
    {
        $toBeDeAssigned = count($productLabelProductAbstractRelationTransfer->getIdsProductAbstractToDeAssign());

        if (!$toBeDeAssigned) {
            return;
        }

        $this->info(sprintf(
            '%s - Deassigning %d products from label #%d.',
            $pluginName,
            $toBeDeAssigned,
            $productLabelProductAbstractRelationTransfer->getIdProductLabel()
        ));

        $this->productAbstractRelationDeleter->removeRelations(
            $productLabelProductAbstractRelationTransfer->getIdProductLabel(),
            $productLabelProductAbstractRelationTransfer->getIdsProductAbstractToDeAssign()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer $productLabelProductAbstractRelationTransfer
     * @param string $pluginName
     *
     * @return void
     */
    protected function assign(ProductLabelProductAbstractRelationsTransfer $productLabelProductAbstractRelationTransfer, $pluginName)
    {
        $toBeAssigned = count($productLabelProductAbstractRelationTransfer->getIdsProductAbstractToAssign());

        if (!$toBeAssigned) {
            return;
        }

        $this->info(sprintf(
            '%s - Assigning %d products to label #%d.',
            $pluginName,
            $toBeAssigned,
            $productLabelProductAbstractRelationTransfer->getIdProductLabel()
        ));

        $this->productAbstractRelationWriter->addRelations(
            $productLabelProductAbstractRelationTransfer->getIdProductLabel(),
            $productLabelProductAbstractRelationTransfer->getIdsProductAbstractToAssign()
        );
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function info($message)
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function debug($message)
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->debug($message);
    }
}
