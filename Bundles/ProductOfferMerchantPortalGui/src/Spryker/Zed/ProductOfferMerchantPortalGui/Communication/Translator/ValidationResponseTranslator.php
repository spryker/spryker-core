<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Translator;

use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ValidationResponseTranslator implements ValidationResponseTranslatorInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function translateValidationResponse(ValidationResponseTransfer $validationResponseTransfer): ValidationResponseTransfer
    {
        foreach ($validationResponseTransfer->getValidationErrors() as $validationErrorTransfer) {
            $validationErrorTransfer->setMessage(
                $this->translatorFacade->trans($validationErrorTransfer->getMessageOrFail()),
            );
        }

        return $validationResponseTransfer;
    }
}
