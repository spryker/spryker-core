<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OauthWarehouseUser\Business\OauthWarehouseUserBusinessFactory getFactory()
 * @method \Spryker\Zed\OauthWarehouseUser\Persistence\OauthWarehouseUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\OauthWarehouseUser\Persistence\OauthWarehouseUserEntityManagerInterface getEntityManager()
 */
class OauthWarehouseUserFacade extends AbstractFacade implements OauthWarehouseUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getWarehouseUserTypeOauthScopes(UserIdentifierTransfer $userIdentifierTransfer): array
    {
        return $this->getFactory()
            ->createWarehouseUserTypeOauthScopeProvider()
            ->getScopes($userIdentifierTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorizeByWarehouseUserScope(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFactory()
            ->createWarehouseUserTypeOauthScopeAuthorizationChecker()
            ->authorize($authorizationRequestTransfer);
    }
}
