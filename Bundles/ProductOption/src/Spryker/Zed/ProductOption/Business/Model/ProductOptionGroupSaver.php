<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionGroupSaver
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     */
    public function __construct(ProductOptionQueryContainerInterface $productOptionQueryContainer)
    {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $productOptionGroupEntity = $this->createProductOptionGroupEntity();
        $this->hydrateProductOptionGroupEntity($productOptionGroupTransfer, $productOptionGroupEntity);
        $productOptionGroupEntity->save();

        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionValueTransfer->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());
            $this->saveProductOptionValue($productOptionValueTransfer);
        }

        return $productOptionGroupEntity->getIdProductOptionGroup();
    }

    /**
     * @param string $abstractSku
     * @param int $idProductOptionGroup
     *
     * @return bool|void
     */
    public function addProductAbstractToProductOptionGroup($abstractSku, $idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupByIdProductOptionGroup($idProductOptionGroup)
            ->findOne();

        if (!$productOptionGroupEntity) {
            return;
        }

        $productAbstractEntity = $this->productOptionQueryContainer
            ->queryProductAbstractBySku($abstractSku)
            ->findOne();

        if (!$productAbstractEntity) {
            return;
        }

        $productOptionGroupEntity->addSpyProductAbstract($productAbstractEntity);

        return $productOptionGroupEntity->save() > 0;

    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return int
     */
    public function saveProductOptionValue(ProductOptionValueTransfer $productOptionValueTransfer)
    {
        $productOptionValueTransfer->requireFkProductOptionGroup()
            ->requirePrice()
            ->requireSku()
            ->requireValue();

        $producOptionValueEntity = $this->createProductOptionValueEntity();

        $this->hydrateOptionValueEntity($producOptionValueEntity, $productOptionValueTransfer);

        $producOptionValueEntity->save();

        return $producOptionValueEntity->getIdProductOptionValue();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function hydrateProductOptionGroupEntity(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        $productOptionGroupEntity->fromArray($productOptionGroupTransfer->toArray());
    }


    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $producOptionValueEntity
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return void
     */
    protected function hydrateOptionValueEntity(
        SpyProductOptionValue $producOptionValueEntity,
        ProductOptionValueTransfer $productOptionValueTransfer
    ) {
        $producOptionValueEntity->fromArray($productOptionValueTransfer->toArray());
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function createProductOptionGroupEntity()
    {
        return new SpyProductOptionGroup();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntity()
    {
        return new SpyProductOptionValue();
    }



}
