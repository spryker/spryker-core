<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

class AttributeTranslator implements AttributeTranslatorInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductAttributeQueryContainerInterface $productManagementQueryContainer,
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->productAttributeQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     *
     * @return void
     */
    public function saveProductManagementAttributeTranslation(ProductManagementAttributeTransfer $attributeTransfer)
    {
        $attributeTransfer->requireIdProductManagementAttribute();

        $this->saveAttributeKeyTranslations($attributeTransfer);
        $this->resetAttributeValueTranslations($attributeTransfer->getIdProductManagementAttribute());
        $this->saveAttributeValueTranslations($attributeTransfer->getValues());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     *
     * @return void
     */
    protected function saveAttributeKeyTranslations(ProductManagementAttributeTransfer $attributeTransfer)
    {
        foreach ($attributeTransfer->getLocalizedKeys() as $localizedAttributeKeyTransfer) {
            $localizedAttributeKeyTransfer->requireLocaleName();
            $localeTransfer = $this->getLocaleByName($localizedAttributeKeyTransfer->getLocaleName());

            $this->saveAttributeKeyToGlossary($attributeTransfer->getKey(), $localizedAttributeKeyTransfer->getKeyTranslation(), $localeTransfer);
        }
    }

    /**
     * @param string $key
     * @param string $keyTranslation
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveAttributeKeyToGlossary($key, $keyTranslation, LocaleTransfer $localeTransfer)
    {
        $attributeGlossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($key);

        if ($this->glossaryFacade->hasTranslation($attributeGlossaryKey, $localeTransfer)) {
            $this->glossaryFacade->updateAndTouchTranslation(
                $attributeGlossaryKey,
                $localeTransfer,
                $keyTranslation,
                true
            );

            return;
        }

        $this->glossaryFacade->createAndTouchTranslation(
            $attributeGlossaryKey,
            $localeTransfer,
            $keyTranslation,
            true
        );
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return void
     */
    protected function resetAttributeValueTranslations($idProductManagementAttribute)
    {
        $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueTranslationById($idProductManagementAttribute)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]|\ArrayObject $attributeValueTransfers
     *
     * @return void
     */
    protected function saveAttributeValueTranslations(ArrayObject $attributeValueTransfers)
    {
        foreach ($attributeValueTransfers as $attributeValueTransfer) {
            $attributeValueTransfer->requireIdProductManagementAttributeValue();

            foreach ($attributeValueTransfer->getLocalizedValues() as $localizedAttributeValueTransfer) {
                $this->saveAttributeValueTranslation($attributeValueTransfer->getIdProductManagementAttributeValue(), $localizedAttributeValueTransfer);
            }
        }
    }

    /**
     * @param int $idProductManagementAttributeValue
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer $localizedAttributeValueTransfer
     *
     * @return void
     */
    protected function saveAttributeValueTranslation(
        $idProductManagementAttributeValue,
        ProductManagementAttributeValueTranslationTransfer $localizedAttributeValueTransfer
    ) {
        $translation = trim($localizedAttributeValueTransfer->getTranslation());

        if ($translation === '') {
            return;
        }

        $localizedAttributeValueTransfer->requireFkLocale();

        $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
        $attributeValueTranslationEntity
            ->setFkLocale($localizedAttributeValueTransfer->getFkLocale())
            ->setFkProductManagementAttributeValue($idProductManagementAttributeValue)
            ->setTranslation($translation);

        $attributeValueTranslationEntity->save();
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleByName($localeName)
    {
        return $this->localeFacade->getLocale($localeName);
    }
}
