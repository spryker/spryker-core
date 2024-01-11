<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider;

use Spryker\Zed\UserMerchantPortalGui\Communication\Form\ChangeEmailForm;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;

class ChangeEmailFormDataProvider
{
    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        UserMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return [
            ChangeEmailForm::KEY_ID_USER => $this->merchantUserFacade->getCurrentMerchantUser()->getIdUserOrFail(),
        ];
    }

    /**
     * @param bool $isEmailUniquenessValidationEnabled
     *
     * @return array<string, mixed>
     */
    public function getOptions(bool $isEmailUniquenessValidationEnabled = true): array
    {
        return [
            ChangeEmailForm::OPTION_IS_EMAIL_UNIQUENESS_VALIDATION_ENABLED => $isEmailUniquenessValidationEnabled,
        ];
    }
}
