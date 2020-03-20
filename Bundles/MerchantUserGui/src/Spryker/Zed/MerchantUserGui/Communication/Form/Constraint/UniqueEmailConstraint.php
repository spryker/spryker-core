<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueEmailConstraint extends SymfonyConstraint
{
    public const OPTION_USER_FACADE = 'userFacade';

    protected const MESSAGE = 'User with email "{{ username }}" already exists.';

    /**
     * @var \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToUserFacadeInterface
     */
    public function getUserFacade(): MerchantUserGuiToUserFacadeInterface
    {
        return $this->userFacade;
    }
}
