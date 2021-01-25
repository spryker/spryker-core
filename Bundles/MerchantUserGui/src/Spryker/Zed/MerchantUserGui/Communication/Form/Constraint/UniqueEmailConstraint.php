<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueEmailConstraint extends SymfonyConstraint
{
    public const OPTION_MERCHANT_USER_FACADE = 'merchantUserFacade';

    protected const MESSAGE = 'User with email "{{ username }}" already exists.';

    /**
     * @var \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->merchantUserFacade;
    }
}
