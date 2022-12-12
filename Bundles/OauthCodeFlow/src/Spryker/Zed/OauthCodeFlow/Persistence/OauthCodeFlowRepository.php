<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Persistence;

use Generated\Shared\Transfer\AuthCodeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowPersistenceFactory getFactory()
 */
class OauthCodeFlowRepository extends AbstractRepository implements OauthCodeFlowRepositoryInterface
{
    /**
     * @param string $codeId
     *
     * @return \Generated\Shared\Transfer\AuthCodeTransfer|null
     */
    public function findAuthCodeByIdentifier(string $codeId): ?AuthCodeTransfer
    {
        $authCodeEntity = $this->getFactory()
            ->createAuthCodeQuery()
            ->filterByIdentifier($codeId)
            ->findOne();

        if ($authCodeEntity === null) {
            return null;
        }

        return $this->getFactory()->createAuthCodeMapper()->mapAuthCodeEntityToAuthCodeTransfer($authCodeEntity, new AuthCodeTransfer());
    }
}
