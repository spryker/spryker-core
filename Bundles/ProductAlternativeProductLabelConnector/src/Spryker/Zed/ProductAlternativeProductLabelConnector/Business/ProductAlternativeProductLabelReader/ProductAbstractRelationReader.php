<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelReader;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    public function __construct(
        ProductAlternativeProductLabelConnectorToProductInterface $productFacade,
        ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade,
        ProductAlternativeProductLabelConnectorConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
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
        $idsToAssign = [];
        $idsToDeAssign = [];

        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        foreach ($this->productAlternativeFacade->findProductAbstractIdsWhichConcreteHasAlternative() as $idProductAbstract) {
            $concreteIds = $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);

            if (!$this->productAlternativeFacade->doAllConcreteProductsHaveAlternatives($concreteIds)) {
                $idsToDeAssign[] = $idProductAbstract;

                continue;
            }

            if (!in_array($idProductLabel, $this->productLabelFacade->findActiveLabelIdsByIdProductAbstract($idProductAbstract))) {
                $idsToAssign[] = $idProductAbstract;
            }
        }

        return [$this->mapRelationTransfer(
            $idProductLabel,
            $idsToAssign,
            $idsToDeAssign
        )];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected function findProductAlternativeProductLabel(): ?ProductLabelTransfer
    {
        return $this->productLabelFacade->findLabelByLabelName(
            $this->config->getProductAlternativesLabelName()
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
