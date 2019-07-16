<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\ProductOptionEvents;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class AbstractProductOptionSaver implements AbstractProductOptionSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface $eventFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchFacadeInterface $touchFacade,
        ProductOptionToEventFacadeInterface $eventFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->eventFacade = $eventFacade;
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

            $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
                    SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT => $idProductAbstract,
            ]);

            $this->eventFacade->trigger(ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_DELETE, $eventTransfer);
            $this->touchFacade->touchActive(ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION, $idProductAbstract);
        }
    }

    /**
     * @param string $abstractSku
     * @param int $idProductOptionGroup
     *
     * @return bool
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->getOptionGroupById((int)$idProductOptionGroup);
        $productAbstractEntity = $this->getProductAbstractBySku($abstractSku);

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
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getOptionGroupById($idProductOptionGroup): SpyProductOptionGroup
    {
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupById($idProductOptionGroup)
            ->findOne();

        if (!$productOptionGroupEntity) {
            throw new ProductOptionGroupNotFoundException(sprintf(
                'Product Option Group with id "%s" not found.',
                $idProductOptionGroup
            ));
        }

        return $productOptionGroupEntity;
    }

    /**
     * @param string $abstractSku
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractBySku($abstractSku): SpyProductAbstract
    {
        $productAbstractEntity = $this->productOptionQueryContainer
            ->queryProductAbstractBySku($abstractSku)
            ->findOne();

        if (!$productAbstractEntity) {
            throw new AbstractProductNotFoundException(sprintf(
                'Abstract Product with sku "%s" not found.',
                $abstractSku
            ));
        }

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
            if ($productAbstractEntity->getIdProductAbstract() === $idProductAbstract) {
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
        $productAbstractProductOptionGroupEntity->setFkProductAbstract($idProductAbstract);
        $productAbstractProductOptionGroupEntity->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());

        return $productAbstractProductOptionGroupEntity;
    }
}
