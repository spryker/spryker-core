<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Spryker\Zed\UserMerchantPortalGui\Communication\Form\MerchantAccountForm;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantAccountFormDataProvider implements MerchantAccountFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface
     */
    protected UserMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        UserMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return [
            MerchantAccountForm::OPTIONS_LOCALE => $this->getLocales(),
        ];
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->getUserOrFail()
            ->toArray();
    }

    /**
     * @return array
     */
    protected function getLocales(): array
    {
        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
            ->setLocaleConditions(
                (new LocaleConditionsTransfer())
                    ->setLocaleNames($this->localeFacade->getSupportedLocaleCodes()),
            );

        $localeTransfers = $this->localeFacade->getLocaleCollection($localeCriteriaTransfer);

        $options = [];

        foreach ($localeTransfers as $localeTransfer) {
            $options[$localeTransfer->getLocaleName()] = $localeTransfer->getIdLocale();
        }

        return $options;
    }
}
