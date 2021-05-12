<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserBusinessFactory getFactory()
 */
class SecurityOauthUserFacade extends AbstractFacade implements SecurityOauthUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerResponseTransfer
     */
    public function getResourceOwner(
        ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
    ): ResourceOwnerResponseTransfer {
        return $this->getFactory()
            ->createResourceOwnerReader()
            ->getResourceOwner($resourceOwnerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isOauthUserRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer {
        return $this->getFactory()
            ->createOauthUserRestrictionChecker()
            ->isOauthUserRestricted($oauthUserRestrictionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function resolveOauthUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        return $this->getFactory()
            ->createAuthenticationStrategyExecutor()
            ->resolveOauthUser($userCriteriaTransfer);
    }
}
