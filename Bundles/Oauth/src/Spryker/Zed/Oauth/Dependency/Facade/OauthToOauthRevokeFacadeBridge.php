<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Dependency\Facade;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;

class OauthToOauthRevokeFacadeBridge implements OauthToOauthRevokeFacadeInterface
{
    /**
     * @var \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface
     */
    protected $oauthRevokeFacade;

    /**
     * @param $oauthRevokeFacade
     */
    public function __construct($oauthRevokeFacade)
    {
        $this->oauthRevokeFacade = $oauthRevokeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findOne(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        return $this->oauthRevokeFacade->findOne($oauthTokenCriteriaFilterTransfer);
    }
}
