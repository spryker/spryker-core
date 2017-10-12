<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class AbstractProductOptionSaver implements AbstractProductOptionSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface $touchFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchInterface $touchFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    public function assignProducts(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        foreach ($productOptionGroupTransfer->getProductsToBeAssigned() as $idProductAbstract) {
            if ($this->isProductAlreadyInGroup($productOptionGroupEntity, $idProductAbstract)) {
                continue;
            }

            $productAbstractProductOptionGroupEntity = $this->createProductAbstractProductOptionGroupEntity(
                $productOptionGroupEntity,
                $idProductAbstract
            );

            $productOptionGroupEntity->addSpyProductAbstractProductOptionGroup($productAbstractProductOptionGroupEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    public function deAssignProducts(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        foreach ($productOptionGroupTransfer->getProductsToBeDeAssigned() as $idProductAbstract) {
            $productAbstractProductOptionGroupEntity = $this->createProductAbstractProductOptionGroupEntity($productOptionGroupEntity, $idProductAbstract);
            $productOptionGroupEntity->removeSpyProductAbstractProductOptionGroup($productAbstractProductOptionGroupEntity);

            $this->touchFacade->touchActive(ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION, $idProductAbstract);
        }
    }

    /**
     * @param string $abstractSku
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return bool
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->getOptionGroupById($idProductOptionGroup);
        if (!$productOptionGroupEntity) {
            throw new ProductOptionGroupNotFoundException(sprintf(
                'Product option group with id "%s" not found.',
                $idProductOptionGroup
            ));
        }

        $productAbstractEntity = $this->getProductAbstractBySku($abstractSku);
        if (!$productAbstractEntity) {
            throw new AbstractProductNotFoundException(sprintf(
                'Abstract product with sku "%s" not found.',
                $abstractSku
            ));
        }

        $productOptionGroupEntity->addSpyProductAbstract($productAbstractEntity);

        $affectedRows = $productOptionGroupEntity->save();

        if ($affectedRows > 0) {
            $this->touchFacade->touchActive(
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION,
                $productAbstractEntity->getIdProductAbstract()
            );

            return true;
        }

        return false;
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getOptionGroupById($idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupById((int)$idProductOptionGroup)
            ->findOne();

        return $productOptionGroupEntity;
    }

    /**
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractBySku($abstractSku)
    {
        $productAbstractEntity = $this->productOptionQueryContainer
            ->queryProductAbstractBySku($abstractSku)
            ->findOne();

        return $productAbstractEntity;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function isProductAlreadyInGroup(SpyProductOptionGroup $productOptionGroupEntity, $idProductAbstract)
    {
        foreach ($productOptionGroupEntity->getSpyProductAbstracts() as $productAbstractEntity) {
            if ((int)$productAbstractEntity->getIdProductAbstract() === (int)$idProductAbstract) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup
     */
    protected function createProductAbstractProductOptionGroupEntity(SpyProductOptionGroup $productOptionGroupEntity, $idProductAbstract)
    {
        $productAbstractProductOptionGroupEntity = new SpyProductAbstractProductOptionGroup();
        $productAbstractProductOptionGroupEntity->setFkProductAbstract((int)$idProductAbstract);
        $productAbstractProductOptionGroupEntity->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());

        return $productAbstractProductOptionGroupEntity;
    }
}
