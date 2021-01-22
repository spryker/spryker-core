<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Business\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface as SymfonyAuthorizationCheckerInterface;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @uses \Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter::IS_AUTHENTICATED_FULLY
     */
    protected const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SymfonyAuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        return $this->authorizationChecker->isGranted(static::IS_AUTHENTICATED_FULLY);
    }
}
