<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider;

use Spryker\Zed\UserMerchantPortalGui\Communication\Form\MerchantAccountForm;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantAccountFormDataProvider implements MerchantAccountFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

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
     * @return mixed[]
     */
    public function getOptions(): array
    {
        return [
            MerchantAccountForm::OPTIONS_LOCALE => array_flip(
                $this->localeFacade->getAvailableLocales()
            ),
        ];
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->getUserOrFail()
            ->toArray();
    }
}
