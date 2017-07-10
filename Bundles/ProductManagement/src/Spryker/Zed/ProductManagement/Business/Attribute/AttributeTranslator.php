<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeTranslator implements AttributeTranslatorInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function saveProductManagementAttributeTranslation(ProductManagementAttributeTransfer $attributeTransfer)
    {
        $attributeTransfer->requireIdProductManagementAttribute();

        $this->productManagementQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $this->saveAttributeKeyTranslations($attributeTransfer);
            $this->resetAttributeValueTranslations($attributeTransfer->getIdProductManagementAttribute());
            $this->saveAttributeValueTranslations($attributeTransfer->getValues());

            $this->productManagementQueryContainer
                ->getConnection()
                ->commit();

        } catch (\Exception $e) {
            $this->productManagementQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }
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
        $this->productManagementQueryContainer
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
    protected function saveAttributeValueTranslation($idProductManagementAttributeValue, ProductManagementAttributeValueTranslationTransfer $localizedAttributeValueTransfer)
    {
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
