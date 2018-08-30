<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form;

use Spryker\Zed\ProductManagement\Communication\Form\Product\Concrete\ProductConcreteSuperAttributeForm;

trait ProductConcreteSuperAttributeFormTrait
{
    /**
     * @param array $submittedAttributes
     *
     * @return array
     */
    protected function getTransformedSubmittedSuperAttributes(array $submittedAttributes)
    {
        $attributes = [];

        foreach ($submittedAttributes as $attributeKey => $attributeData) {
            if (!empty($attributeData[ProductConcreteSuperAttributeForm::FIELD_CHECKBOX])) {
                $attributes[$attributeKey] = $attributeData[ProductConcreteSuperAttributeForm::FIELD_INPUT] ?? null;
                continue;
            }

            $attributes[$attributeKey] = $attributeData[ProductConcreteSuperAttributeForm::FIELD_DROPDOWN] ?? null;
        }

        return $attributes;
    }
}
