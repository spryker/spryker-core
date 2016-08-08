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
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
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
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchInterface $touchFacade,
        ProductOptionToGlossaryInterface $glossaryFacade,
        ProductOptionToLocaleInterface $localeFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
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
        $this->addTranslations($productOptionGroupTransfer);

        $this->touchProductOptionGroupAbstractProducts($productOptionGroupEntity);

        $productOptionGroupEntity->save();

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
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupById($idProductOptionGroup)
            ->findOne();

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
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param SpyProductOptionGroup $productOptionGroupEntity
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
     * @param SpyProductOptionGroup $productOptionGroupEntity
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

            $productAbstractProductOptionGroupEntity = $this->createProductAbstractProductOptionGroupEntity($productOptionGroupEntity, $idProductAbstract);
            $productOptionGroupEntity->addSpyProductAbstractProductOptionGroup($productAbstractProductOptionGroupEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param SpyProductOptionGroup $productOptionGroupEntity
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

            $this->touchFacade->touchActive(
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION,
                $idProductAbstract
            );
        }

    }

    /**
     * @param ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param SpyProductOptionGroup  $productOptionGroupEntity
     *
     * @return void
     */
    protected function removeOptionValues(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        SpyProductOptionGroup $productOptionGroupEntity
    ) {
        foreach ($productOptionGroupTransfer->getProductOptionValuesToBeRemoved() as $idProductOptionValue) {
            $productOptionValueEntity = $this->getProductOptionValueEntity($idProductOptionValue);

            $this->glossaryFacade->deleteKey($productOptionValueEntity->getValue());

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
     *
     * @return void
     */
    protected function addTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        $this->addValueTranslations($productOptionGroupTransfer);
        $this->addGroupNameTranslations($productOptionGroupTransfer);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $localeCode
     *
     * @return void
     */
    protected function saveProductOptionTranslation($key, $value, $localeCode)
    {
        if (!$this->glossaryFacade->hasKey($key)) {
            $this->glossaryFacade->createKey($key);
        }

        $localeTransfer = $this->localeFacade->getLocaleByCode($localeCode);
        if (!$this->glossaryFacade->hasTranslation($key, $localeTransfer)) {
            $this->glossaryFacade->createAndTouchTranslation($key, $localeTransfer, $value);
        } else {
            $this->glossaryFacade->updateAndTouchTranslation($key, $localeTransfer, $value);
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
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupByIdProductOptionGroup($idProductOptionGroup)
            ->findOne();

        if (!$productOptionGroupEntity) {
            return false;
        }

        $productAbstractEntity = $this->productOptionQueryContainer
            ->queryProductAbstractBySku($abstractSku)
            ->findOne();

        if (!$productAbstractEntity) {
            return false;
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
            $productOptionValueTransfer->setValue(
                ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $productOptionValueTransfer->getValue()
            );
        }

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

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getProductOptionGroupEntity(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        if ($productOptionGroupTransfer->getIdProductOptionGroup()) {
            return $this->productOptionQueryContainer
                ->queryProductOptionGroupById($productOptionGroupTransfer->getIdProductOptionGroup())
                ->findOne();
        }

        return $this->createProductOptionGroupEntity();
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function getProductOptionValueEntity($idProductOptionValue = null)
    {
        if ($idProductOptionValue) {
            return $this->productOptionQueryContainer
                ->queryProductOptionByValueId((int)$idProductOptionValue)
                ->findOne();
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
        foreach ($productOptionGroupEntity->getSpyProductAbstracts() as $productAbstractEntity) {
            $this->touchFacade->touchActive(
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION,
                $productAbstractEntity->getIdProductAbstract()
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
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addValueTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        foreach ($productOptionGroupTransfer->getProductOptionValueTranslations() as $productOptionTranslationTransfer) {

            $value = $productOptionTranslationTransfer->getName();
            $key = $productOptionTranslationTransfer->getKey();

            if (!$value) {
                $value = $key;
            }

            if (strpos($key, ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX) === false) {
                $key = ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $key;
            }

            $this->saveProductOptionTranslation(
                $key,
                $value,
                $productOptionTranslationTransfer->getLocaleCode()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        if (!$productOptionGroupTransfer->getName()) {
            return;
        }

        foreach ($productOptionGroupTransfer->getGroupNameTranslations() as $groupNameTranslationTransfer) {

            $value = $groupNameTranslationTransfer->getName();
            $key = $productOptionGroupTransfer->getName();

            if (!$value) {
                $value = $key;
            }

            $this->saveProductOptionTranslation(
                $key,
                $value,
                $groupNameTranslationTransfer->getLocaleCode()
            );
        }
    }


}
