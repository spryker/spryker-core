<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelWriter;


class ProductAlternativeProductLabelWriter implements ProductAlternativeProductLabelWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductBridge
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductLabelBridge
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge
     */
    protected $productAlternativeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig
     */
    protected $config;

    public function __construct(
        $productFacade,
        $productLabelFacade,
        $productAlternativeFacade,
        $config
    )
    {
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
        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteId($idProduct);
        $concreteIds = [];

        foreach ($this->productFacade->getConcreteProductsByAbstractProductId($idProductAbstract) as $productConcreteTransfer) {
            $concreteIds[] = $productConcreteTransfer->getIdProductConcrete();
        }

        $idProductLabel = $this->productLabelFacade->findLabelByLabelName(
            $this->config->getProductAlternativesLabel()
        )->getIdProductLabel();

        if ($this->productAlternativeFacade->doAllConcreteProductsHaveAlternatives($concreteIds)) {
            $this->productLabelFacade->addAbstractProductRelationsForLabel($idProductLabel, $idProductAbstract);
        } else {
            $this->productLabelFacade->removeProductAbstractRelationsForLabel($idProductLabel, $idProductAbstract);
        }
    }
}
