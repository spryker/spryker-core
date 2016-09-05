<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\Exception\AbstractProductNotFoundException;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class ProductOptionGroupSaver implements ProductOptionGroupSaverInterface
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
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected $translationSaver;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface $translationSaver
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchInterface $touchFacade,
        TranslationSaverInterface $translationSaver
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->translationSaver = $translationSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return int
     */
    public function saveProductOptionGroup(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $productOptionGroupEntity = $this->getProductOptionGroupEntity($productOptionGroupTransfer);
        $this->hydrateProductOptionGroupEntity($productOptionGroupTransfer, $productOptionGroupEntity);
        $productOptionGroupEntity->save();

        $this->saveOptionValues($productOptionGroupTransfer, $productOptionGroupEntity);
        $this->removeOptionValues($productOptionGroupTransfer, $productOptionGroupEntity);
        $this->assignProducts($productOptionGroupTransfer, $productOptionGroupEntity);
        $this->deAssignProducts($productOptionGroupTransfer, $productOptionGroupEntity);

        $this->translationSaver->addGroupNameTranslations($productOptionGroupTransfer);
        $this->translationSaver->addValueTranslations($productOptionGroupTransfer);

        $this->touchProductOptionGroupAbstractProducts($productOptionGroupEntity);

        $productOptionGroupEntity->save();

        $productOptionGroupTransfer->setIdProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());

        return $productOptionGroupEntity->getIdProductOptionGroup();
    }

    /**
     * @param int $idProductOptionGroup
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return bool
     */
    public function toggleOptionActive($idProductOptionGroup, $isActive)
    {
        $productOptionGroupEntity = $this->getOptionGroupById($idProductOptionGroup);

        if (!$productOptionGroupEntity) {
            throw new ProductOptionGroupNotFoundException(
                sprintf('Product option group with id "%d" not found', $idProductOptionGroup)
            );
        }

        $this->touchProductOptionGroupAbstractProducts($productOptionGroupEntity);

        $productOptionGroupEntity->setActive($isActive);

        return $productOptionGroupEntity->save() > 0;
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
            throw new ProductOptionGroupNotFoundException(
                'Product option group with id "%d" not found.',
                $idProductOptionGroup
            );
        }

        $productAbstractEntity = $this->getProductAbstractBySku($abstractSku);
        if (!$productAbstractEntity) {
            throw new AbstractProductNotFoundException(
                'Abstract product with sku "%d" not found.',
                $abstractSku
            );
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

        $producOptionValueEntity = $this->getProductOptionValueEntity($productOptionValueTransfer->getIdProductOptionValue());
        $this->hydrateOptionValueEntity($producOptionValueEntity, $productOptionValueTransfer);
        $producOptionValueEntity->save();

        $productOptionValueTransfer->setIdProductOptionValue($producOptionValueEntity->getIdProductOptionValue());

        return $producOptionValueEntity->getIdProductOptionValue();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function saveOptionValues(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionValueTransfer->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());
            $this->saveProductOptionValue($productOptionValueTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function assignProducts(
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
    protected function deAssignProducts(
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
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function removeOptionValues(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        foreach ($productOptionGroupTransfer->getProductOptionValuesToBeRemoved() as $idProductOptionValue) {
            if (!$idProductOptionValue) {
                continue;
            }
            $productOptionValueEntity = $this->getProductOptionValueEntity($idProductOptionValue);

            $this->translationSaver->deleteTranslation($productOptionValueEntity->getValue());

            $productOptionGroupEntity->removeSpyProductOptionValue($productOptionValueEntity);
        }
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

        if ($productOptionGroupTransfer->getName() &&
            strpos($productOptionGroupTransfer->getName(), ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX) === false) {

            $productOptionGroupTransfer->setName(
                ProductOptionConfig::PRODUCT_OPTION_GROUP_NAME_TRANSLATION_PREFIX . $productOptionGroupTransfer->getName()
            );
        }

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
        if (!$producOptionValueEntity->getValue()) {
            if (strpos($productOptionValueTransfer->getValue(), ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX) === false) {
                $productOptionValueTransfer->setValue(
                    ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $productOptionValueTransfer->getValue()
                );
            }
        }

        $producOptionValueEntity->fromArray($productOptionValueTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getProductOptionGroupEntity(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        if ($productOptionGroupTransfer->getIdProductOptionGroup()) {
            return $this->getOptionGroupById($productOptionGroupTransfer->getIdProductOptionGroup());
        }

        return $this->createProductOptionGroupEntity();
    }

    /**
     * @param int|null $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function getProductOptionValueEntity($idProductOptionValue = null)
    {
        if ($idProductOptionValue) {
            return $this->getProductOptionValueById($idProductOptionValue);
        }

        return $this->createProductOptionValueEntity();
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    protected function touchProductOptionGroupAbstractProducts(SpyProductOptionGroup $productOptionGroupEntity)
    {
        foreach ($productOptionGroupEntity->getSpyProductAbstractProductOptionGroups() as $productAbstractProductOptionEntity) {
            $this->touchFacade->touchActive(
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION,
                $productAbstractProductOptionEntity->getFkProductAbstract()
            );
        }
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
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function getProductOptionValueById($idProductOptionValue)
    {
        $productOptionValueEntity = $this->productOptionQueryContainer
            ->queryProductOptionByValueId((int)$idProductOptionValue)
            ->findOne();

        return $productOptionValueEntity;
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
