<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Transfer;

use Generated\Shared\Transfer\LocalizedProductSearchAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Spryker\Zed\ProductSearch\Communication\Form\AttributeTranslationForm;
use Spryker\Zed\ProductSearch\Communication\Form\FilterPreferencesForm;
use Symfony\Component\Form\FormInterface;

class AttributeFormTransferMapper implements AttributeFormTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $attributeForm
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function createTransfer(FormInterface $attributeForm)
    {
        $attributeTransfer = (new ProductSearchAttributeTransfer())
            ->fromArray($attributeForm->getData(), true);

        $translations = (array)$attributeForm->get(FilterPreferencesForm::FIELD_TRANSLATIONS)->getData();

        $attributeTransfer = $this->addLocalizedKeys($attributeTransfer, $translations);

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $attributeTransfer
     * @param array $translations
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function addLocalizedKeys(ProductSearchAttributeTransfer $attributeTransfer, array $translations)
    {
        foreach ($translations as $localeName => $translateFormData) {
            $localizedKeyTransfer = new LocalizedProductSearchAttributeKeyTransfer();
            $localizedKeyTransfer
                ->setLocaleName($localeName)
                ->setKeyTranslation($translateFormData[AttributeTranslationForm::FIELD_KEY_TRANSLATION]);

            $attributeTransfer->addLocalizedKey($localizedKeyTransfer);
        }

        return $attributeTransfer;
    }
}
