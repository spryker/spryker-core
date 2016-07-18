<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer;
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer[] $attributeTranslationFormTransfers
     *
     * @throws \Exception
     *
     * @return void
     */
    public function saveProductManagementAttributeTranslation(array $attributeTranslationFormTransfers)
    {
        $this->productManagementQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            foreach ($attributeTranslationFormTransfers as $attributeTranslationFormTransfer) {
                $localeTransfer = $this->localeFacade->getLocale($attributeTranslationFormTransfer->getLocaleName());

                $this->saveAttributeNameToGlossary($attributeTranslationFormTransfer, $localeTransfer);

                if ($attributeTranslationFormTransfer->getTranslateValues()) {
                    $this->saveAttributeValueTranslations($attributeTranslationFormTransfer, $localeTransfer);
                } else {
                    $this->deleteAttributeValueTranslations($attributeTranslationFormTransfer, $localeTransfer);
                }
            }

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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveAttributeNameToGlossary(ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer, LocaleTransfer $localeTransfer)
    {
        $attributeGlossaryKey = $this->generateAttributeGlossaryKey($attributeTranslationFormTransfer->getAttributeName());

        if ($this->glossaryFacade->hasTranslation($attributeGlossaryKey, $localeTransfer)) {
            $this->glossaryFacade->updateAndTouchTranslation(
                $attributeGlossaryKey,
                $localeTransfer,
                $attributeTranslationFormTransfer->getAttributeNameTranslation(),
                true
            );

            return;
        }
        
        $this->glossaryFacade->createAndTouchTranslation(
            $attributeGlossaryKey,
            $localeTransfer,
            $attributeTranslationFormTransfer->getAttributeNameTranslation(),
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveAttributeValueTranslations(ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer, LocaleTransfer $localeTransfer)
    {
        foreach ($attributeTranslationFormTransfer->getValueTranslations() as $valueTranslationTransfer) {
            // TODO: move to query container
            $translationEntity = $this->productManagementQueryContainer
                ->queryProductManagementAttributeValueTranslation()
                ->filterByFkProductManagementAttributeValue($valueTranslationTransfer->getIdProductManagementAttributeValue())
                ->filterByFkLocale($localeTransfer->getIdLocale())
                ->findOneOrCreate();

            $translation = trim($valueTranslationTransfer->getTranslation());

            if ($translation === '') {
                continue;
            }

            $translationEntity
                ->setTranslation($valueTranslationTransfer->getTranslation())
                ->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function deleteAttributeValueTranslations(ProductManagementAttributeTranslationFormTransfer $attributeTranslationFormTransfer, LocaleTransfer $localeTransfer)
    {
        foreach ($attributeTranslationFormTransfer->getValueTranslations() as $valueTranslationTransfer) {
            // TODO: move to query container
            $this->productManagementQueryContainer
                ->queryProductManagementAttributeValueTranslation()
                ->filterByFkProductManagementAttributeValue($valueTranslationTransfer->getIdProductManagementAttributeValue())
                ->filterByFkLocale($localeTransfer->getIdLocale())
                ->delete();
        }
    }

}
