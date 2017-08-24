<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;

interface AttributeTranslationReaderInterface
{

    /**
     * @param string $key
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer
     */
    public function findAttributeTranslationByKey($key, LocaleTransfer $localeTransfer);

}
