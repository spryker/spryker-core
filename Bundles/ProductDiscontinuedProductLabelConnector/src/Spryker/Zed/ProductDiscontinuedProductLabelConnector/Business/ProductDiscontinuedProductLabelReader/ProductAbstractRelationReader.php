<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelReader;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig $config
     */
    protected $config;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface
     */
    protected $productDiscontinuedProductLabelConnectorRepository;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig $config
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Persistence\ProductDiscontinuedProductLabelConnectorRepositoryInterface $productDiscontinuedProductLabelConnectorRepository
     */
    public function __construct(
        ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductDiscontinuedProductLabelConnectorConfig $config,
        ProductDiscontinuedProductLabelConnectorRepositoryInterface $productDiscontinuedProductLabelConnectorRepository
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->config = $config;
        $this->productDiscontinuedProductLabelConnectorRepository = $productDiscontinuedProductLabelConnectorRepository;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        $productLabelTransfer = $this->findProductDiscontinuedProductLabel();
        if (!$productLabelTransfer) {
            return [];
        }

        return $this->getRelationsData($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    protected function getRelationsData(ProductLabelTransfer $productLabelTransfer): array
    {
        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $labeledProductAbstractIds = $this->productLabelFacade->findProductAbstractRelationsByIdProductLabel($idProductLabel);
        $productAbstractIdsToBeLabeled = $this->productDiscontinuedProductLabelConnectorRepository->getProductAbstractIdsToBeLabeled();

        $idsToAssign = array_diff($productAbstractIdsToBeLabeled, $labeledProductAbstractIds);
        $idsToDeAssign = array_diff($labeledProductAbstractIds, $productAbstractIdsToBeLabeled);

        return [$this->mapRelationTransfer(
            $idProductLabel,
            $idsToAssign,
            $idsToDeAssign
        )];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected function findProductDiscontinuedProductLabel(): ?ProductLabelTransfer
    {
        return $this->productLabelFacade->findLabelByLabelName(
            $this->config->getProductDiscontinueLabelName()
        );
    }

    /**
     * @param int $idProductLabel
     * @param int[] $idsToAssign
     * @param int[] $idsToDeAssign
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer
     */
    protected function mapRelationTransfer(
        int $idProductLabel,
        array $idsToAssign,
        array $idsToDeAssign
    ): ProductLabelProductAbstractRelationsTransfer {
        $productLabelProductAbstractRelationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $productLabelProductAbstractRelationsTransfer->setIdProductLabel($idProductLabel);

        if (!empty($idsToAssign)) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToAssign($idsToAssign);
        }

        if (!empty($idsToDeAssign)) {
            $productLabelProductAbstractRelationsTransfer->setIdsProductAbstractToDeAssign($idsToDeAssign);
        }

        return $productLabelProductAbstractRelationsTransfer;
    }
}
