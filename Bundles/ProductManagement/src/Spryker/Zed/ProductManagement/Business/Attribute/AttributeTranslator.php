<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttributeValueTranslation;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
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
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToGlossaryInterface $glossaryFacade
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
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
            foreach ($attributeTransfer->getLocalizedKeys() as $localizedAttributeKeyTransfer) {
                $localizedAttributeKeyTransfer->requireLocaleName();
                $localeTransfer = $this->localeFacade->getLocale($localizedAttributeKeyTransfer->getLocaleName());

                $this->saveAttributeNameToGlossary($attributeTransfer->getKey(), $localizedAttributeKeyTransfer->getKeyTranslation(), $localeTransfer);
            }

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
     * @param string $key
     * @param string $keyTranslation
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveAttributeNameToGlossary($key, $keyTranslation, LocaleTransfer $localeTransfer)
    {
        $attributeGlossaryKey = $this->generateAttributeGlossaryKey($key);

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
     * @param string $attributeName
     *
     * @return string
     */
    protected function generateAttributeGlossaryKey($attributeName)
    {
        return ProductManagementConstants::PRODUCT_MANAGEMENT_ATTRIBUTE_GLOSSARY_PREFIX . $attributeName;
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]|ArrayObject $attributeValueTransfers
     *
     * @return void
     */
    protected function saveAttributeValueTranslations(ArrayObject $attributeValueTransfers)
    {
        foreach ($attributeValueTransfers as $attributeValueTransfer) {
            $attributeValueTransfer->requireIdProductManagementAttributeValue();

            foreach ($attributeValueTransfer->getLocalizedValues() as $localizedAttributeValueTransfer) {
                $translation = trim($localizedAttributeValueTransfer->getTranslation());

                if ($translation === '') {
                    continue;
                }

                $localizedAttributeValueTransfer->requireFkLocale();

                $attributeValueTranslationEntity = new SpyProductManagementAttributeValueTranslation();
                $attributeValueTranslationEntity
                    ->setFkLocale($localizedAttributeValueTransfer->getFkLocale())
                    ->setFkProductManagementAttributeValue($attributeValueTransfer->getIdProductManagementAttributeValue())
                    ->setTranslation($translation);

                $attributeValueTranslationEntity->save();
            }
        }
    }

}
