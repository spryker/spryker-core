<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class ProductOptionGroupSaver
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
        $relatedAbstractProducts = null;
        if ($productOptionGroupTransfer->getIdProductOptionGroup()) {
            $productOptionGroupEntity = $this->productOptionQueryContainer
                ->queryProductOptionGroupById($productOptionGroupTransfer->getIdProductOptionGroup())
                ->findOne();

            $relatedAbstractProducts = $productOptionGroupEntity->getSpyProductAbstracts();
            $productOptionGroupEntity->setSpyProductOptionValues(new Collection());

        } else {
            $productOptionGroupEntity = $this->createProductOptionGroupEntity();
        }

        $this->hydrateProductOptionGroupEntity($productOptionGroupTransfer, $productOptionGroupEntity);
        $productOptionGroupEntity->save();

        $this->saveOptionValues($productOptionGroupTransfer, $productOptionGroupEntity);

        $this->assignProducts($productOptionGroupTransfer, $productOptionGroupEntity);
        //$this->deAssignProducts($productOptionGroupTransfer, $productOptionGroupEntity);

        $productOptionGroupEntity->save();

        if ($relatedAbstractProducts) {
            $productOptionGroupEntity->setSpyProductAbstracts($relatedAbstractProducts);
        }

        return $productOptionGroupEntity->getIdProductOptionGroup();
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
            $productAbstractProductOptionGroupEntity = new SpyProductAbstractProductOptionGroup();
            $productAbstractProductOptionGroupEntity->setFkProductAbstract((int)$idProductAbstract);
            $productAbstractProductOptionGroupEntity->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());
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
        foreach ($productOptionGroupTransfer->getProductsToBeDeassigned() as $idProductAbstract) {
            $productAbstractProductOptionGroupEntity = new SpyProductAbstractProductOptionGroup();
            $productAbstractProductOptionGroupEntity->setFkProductAbstract((int)$idProductAbstract);
            $productAbstractProductOptionGroupEntity->setFkProductOptionGroup($productOptionGroupEntity->getIdProductOptionGroup());
            $productOptionGroupEntity->removeSpyProductAbstractProductOptionGroup($productAbstractProductOptionGroupEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        foreach ($productOptionGroupTransfer->getProductOptionValueTranslations() as $productOptionValueTranslationTransfer) {
            $this->saveProductOptionValueTranslation(
                $productOptionValueTranslationTransfer->getKey(),
                $productOptionValueTranslationTransfer->getName(),
                $productOptionValueTranslationTransfer->getLocaleCode()
            );
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $localeCode
     *
     * @return void
     */
    public function saveProductOptionValueTranslation($key, $value, $localeCode)
    {
        if (!$value) {
            $value = $key;
        }

        $key = ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $key;

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
