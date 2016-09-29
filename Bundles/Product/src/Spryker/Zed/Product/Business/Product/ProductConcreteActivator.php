<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteActivator
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    protected $productManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductUrlGeneratorInterface
     */
    protected $productUrlGenerator;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\ProductManagerInterface $productManager
     * @param \Spryker\Zed\Product\Business\Product\ProductUrlGeneratorInterface $productUrlGenerator
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagerInterface $productManager,
        ProductUrlGeneratorInterface $productUrlGenerator
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManager = $productManager;
        $this->productUrlGenerator = $productUrlGenerator;
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return bool
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $productConcreteEntity = $this->getProductConcreteEntity($idProductConcrete);

        $productConcreteEntity->setIsActive(true);

        $affectedRows = $productConcreteEntity->save();

        $this->productManager->touchProductActive($productConcreteEntity->getFkProductAbstract());
        $this->productUrlGenerator->createAndTouchProductUrls($productConcreteEntity->getFkProductAbstract());

        return $affectedRows > 0;

    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return bool
     */
    public function deActivateProductConcrete($idProductConcrete)
    {
        $productConcreteEntity = $this->getProductConcreteEntity($idProductConcrete);

        $productConcreteEntity->setIsActive(false);

        $affectedRows = $productConcreteEntity->save();

        $this->productManager->touchProductInactive($productConcreteEntity->getFkProductAbstract());

        if ($this->isAllProductAbstractVariantsDeactivated($productConcreteEntity)) {
            //@todo implement url delete
            //$this->urlFacade->touchUrlDeleted();
        }

        return $affectedRows > 0;

    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductConcreteEntity($idProductConcrete)
    {
        $productConcreteEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();

        if (!$productConcreteEntity) {
            throw new ProductConcreteNotFoundException(sprintf(
                'Product concrete with id "%d" not found.',
                $idProductConcrete
            ));
        }

        return $productConcreteEntity;
    }

    /**
     * @param SpyProduct $productConcreteEntity
     *
     * @return bool
     */
    protected function isAllProductAbstractVariantsDeactivated(SpyProduct $productConcreteEntity)
    {
        $productConcreteCollection = $this->productManager->getConcreteProductsByAbstractProductId(
            $productConcreteEntity->getFkProductAbstract()
        );

        foreach ($productConcreteCollection as $productConcreteTransfer) {
            if ($productConcreteTransfer->getIsActive()) {
                return false;
            }
        }

        return true;
    }
}
