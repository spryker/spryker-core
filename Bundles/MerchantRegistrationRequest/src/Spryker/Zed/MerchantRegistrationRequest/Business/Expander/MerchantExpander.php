<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToLocaleFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilTextServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;

class MerchantExpander implements MerchantExpanderInterface
{
    public function __construct(
        protected MerchantRegistrationRequestToLocaleFacadeInterface $localeFacade,
        protected MerchantRegistrationRequestToUtilTextServiceInterface $utilTextService,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    public function expandMerchantTransferWithUrls(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $urlPrefix = $this->getLocalizedUrlPrefix($localeTransfer);
            $merchantTransfer->addUrl(
                (new UrlTransfer())
                    ->setUrl($urlPrefix . $this->utilTextService->generateSlug($merchantTransfer->getNameOrFail()))
                    ->setFkLocale($localeTransfer->getIdLocale()),
            );
        }

        return $merchantTransfer;
    }

    protected function getLocalizedUrlPrefix(LocaleTransfer $localeTransfer): string
    {
        [$languageCode] = explode('_', $localeTransfer->getLocaleNameOrFail());

        return '/' . $languageCode . '/' . $this->merchantRegistrationRequestConfig->getMerchantUrlPrefix() . '/';
    }
}
