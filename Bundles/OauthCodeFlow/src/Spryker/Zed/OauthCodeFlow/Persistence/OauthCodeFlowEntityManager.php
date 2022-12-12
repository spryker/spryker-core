<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowPersistenceFactory getFactory()
 */
class OauthCodeFlowEntityManager extends AbstractEntityManager implements OauthCodeFlowEntityManagerInterface
{
    /**
     * @param string $identifier
     *
     * @return void
     */
    public function deleteAuthCodeByIdentifier(string $identifier): void
    {
        /** @var \Orm\Zed\OauthCodeFlow\Persistence\SpyOauthCodeFlowAuthCode|null $oauthAuthCodeEntity */
        $oauthAuthCodeEntity = $this->getFactory()
            ->createAuthCodeQuery()
            ->findOneByIdentifier($identifier);

        if ($oauthAuthCodeEntity !== null) {
            $oauthAuthCodeEntity->delete();
        }
    }
}
