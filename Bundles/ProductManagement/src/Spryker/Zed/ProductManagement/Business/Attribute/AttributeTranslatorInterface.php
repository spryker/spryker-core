<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

interface AttributeTranslatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTranslationFormTransfer[] $attributeTranslationFormTransfers
     *
     * @return void
     */
    public function saveProductManagementAttributeTranslation(array $attributeTranslationFormTransfers);

}
