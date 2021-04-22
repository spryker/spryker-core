<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\Constraint;

use Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface;
use Symfony\Component\Validator\Constraint;

class UniqueUserEmailConstraint extends Constraint
{
    public const OPTION_MERCHANT_USER_FACADE = 'merchantUserFacade';

    public const GROUP_UNIQUE_USERNAME_CHECK = 'unique_email_check';

    /**
     * @var string[]
     */
    public $groups = [self::GROUP_UNIQUE_USERNAME_CHECK];

    /**
     * @var \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @return \Spryker\Zed\UserMerchantPortalGui\Dependency\Facade\UserMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): UserMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->merchantUserFacade;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'A user with this email already exists.';
    }
}
