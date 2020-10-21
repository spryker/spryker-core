<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelWriter;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig;

class ProductDiscontinuedProductLabelWriter implements ProductDiscontinuedProductLabelWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected static $productLabelCache;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Dependency\Facade\ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig $config
     */
    public function __construct(
        ProductDiscontinuedProductLabelConnectorToProductInterface $productFacade,
        ProductDiscontinuedProductLabelConnectorToProductLabelFacadeInterface $productLabelFacade,
        ProductDiscontinuedProductLabelConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductDiscontinuedProductLabelConnectorConfig $config
    ) {
        $this->productFacade = $productFacade;
        $this->productLabelFacade = $productLabelFacade;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->config = $config;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithDiscontinuedLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductDiscontinuedProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $idProductLabel = $productLabelTransfer->getIdProductLabel();
        $concreteIds = $this->productFacade->findProductConcreteIdsByAbstractProductId($idProductAbstract);

        if (!$this->productDiscontinuedFacade->areAllConcreteProductsDiscontinued($concreteIds)) {
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
    protected function findProductDiscontinuedProductLabel(): ?ProductLabelTransfer
    {
        if (!static::$productLabelCache) {
            static::$productLabelCache = $this->productLabelFacade->findLabelByLabelName(
                $this->config->getProductDiscontinueLabelName()
            );
        }

        return static::$productLabelCache;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void
    {
        $productLabelTransfer = $this->findProductDiscontinuedProductLabel();
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProduct);
        if (!$productLabelTransfer || !$idProductAbstract) {
            return;
        }

        $this->productLabelFacade->removeProductAbstractRelationsForLabel($productLabelTransfer->getIdProductLabel(), [$idProductAbstract]);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabelInBulk(array $productConcreteIds): void
    {
        $productLabelTransfer = $this->findProductDiscontinuedProductLabel();
        if (!$productLabelTransfer) {
            return;
        }

        $productAbstractIds = $this->productFacade->getProductAbstractIdsByProductConcreteIds($productConcreteIds);
        if (!$productAbstractIds) {
            return;
        }

        $this->productLabelFacade->removeProductAbstractRelationsForLabel($productLabelTransfer->getIdProductLabel(), $productAbstractIds);
    }
}
