<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Translator;

use Generated\Shared\Transfer\ValidationResponseTransfer;

interface ValidationResponseTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function translateValidationResponse(ValidationResponseTransfer $validationResponseTransfer): ValidationResponseTransfer;
}
