<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Persistence;

use Generated\Shared\Transfer\AuthCodeTransfer;

/**
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowPersistenceFactory getFactory()
 */
interface OauthCodeFlowRepositoryInterface
{
    /**
     * @param string $codeId
     *
     * @return \Generated\Shared\Transfer\AuthCodeTransfer|null
     */
    public function findAuthCodeByIdentifier(string $codeId): ?AuthCodeTransfer;
}
