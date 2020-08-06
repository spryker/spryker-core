<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class ProductOptionValueSaver implements ProductOptionValueSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaverInterface
     */
    protected $productOptionPriceSaver;

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected $translationSaver;

    /**
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaverInterface $productOptionPriceSaver
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface $translationSaver
     */
    public function __construct(
        ProductOptionValuePriceSaverInterface $productOptionPriceSaver,
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToTouchFacadeInterface $touchFacade,
        TranslationSaverInterface $translationSaver
    ) {
        $this->productOptionPriceSaver = $productOptionPriceSaver;
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->translationSaver = $translationSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return int
     */
    public function saveProductOptionValue(ProductOptionValueTransfer $productOptionValueTransfer)
    {
        $productOptionValueTransfer->requireFkProductOptionGroup()
            ->requirePrices()
            ->requireSku()
            ->requireValue();

        $producOptionValueEntity = $this->getProductOptionValueEntity($productOptionValueTransfer->getIdProductOptionValue());
        $this->hydrateOptionValueEntity($producOptionValueEntity, $productOptionValueTransfer);
        $producOptionValueEntity->save();

        $productOptionValueTransfer->setIdProductOptionValue($producOptionValueEntity->getIdProductOptionValue());
        $this->productOptionPriceSaver->save($productOptionValueTransfer);

        return $producOptionValueEntity->getIdProductOptionValue();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return void
     */
    public function saveOptionValues(
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
    public function removeOptionValues(
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
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $producOptionValueEntity
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return void
     */
    protected function hydrateOptionValueEntity(
        SpyProductOptionValue $producOptionValueEntity,
        ProductOptionValueTransfer $productOptionValueTransfer
    ) {
        if (
            !$producOptionValueEntity->getValue() &&
            strpos($productOptionValueTransfer->getValue(), ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX) === false
        ) {
            $productOptionValueTransfer->setValue(
                ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $productOptionValueTransfer->getValue()
            );
        }

        $producOptionValueEntity->fromArray($productOptionValueTransfer->toArray());
    }

    /**
     * @param int|null $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function getProductOptionValueEntity($idProductOptionValue = null)
    {
        if ($idProductOptionValue !== null) {
            return $this->getProductOptionValueById($idProductOptionValue);
        }

        return $this->createProductOptionValueEntity();
    }

    /**
     * @param int|mixed $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function getProductOptionValueById($idProductOptionValue)
    {
        $productOptionValueEntity = $this->productOptionQueryContainer
            ->queryProductOptionByValueId($idProductOptionValue)
            ->findOne();

        return $productOptionValueEntity;
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    protected function createProductOptionValueEntity()
    {
        return new SpyProductOptionValue();
    }
}
