<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter;

use Generated\Shared\Transfer\ProductLabelTransfer;

class ProductAlternativeProductLabelWriter implements ProductAlternativeProductLabelWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeInterface $productAlternativeFacade
     * @param \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig $config
     */
    public function __construct(
        $productFacade,
        $productLabelFacade,
        $productAlternativeFacade,
        $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productAlternativeFacade = $productAlternativeFacade;
        $this->config = $config;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithAlternativesAvailableLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $concreteIds = $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);

        if (!$this->productAlternativeFacade->doAllConcreteProductsHaveAlternatives($concreteIds)) {
            $this->productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, [$idProductAbstract]);

            return;
        }

        if (!in_array($idProductLabel, $this->productLabelFacade->findActiveLabelIdsByIdProductAbstract($idProductAbstract))) {
            $this->productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, [$idProductAbstract]);
        }
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
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductAlternativeProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $this->productLabelFacade->removeProductAbstractRelationsForLabel($productLabelTransfer->getIdProductLabel(), [$idProductAbstract]);
    }
}
