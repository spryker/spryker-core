<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface TranslationSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function addValueTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function addGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer);

    /**
     * @param string $translationKey
     *
     * @return bool
     */
    public function deleteTranslation($translationKey);
}
