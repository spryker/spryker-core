<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Checker;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;

class OauthUserRestrictionChecker implements OauthUserRestrictionCheckerInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface[]
     */
    protected $oauthUserRestrictionPlugins;

    /**
     * @param \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface[] $oauthUserRestrictionPlugins
     */
    public function __construct(array $oauthUserRestrictionPlugins)
    {
        $this->oauthUserRestrictionPlugins = $oauthUserRestrictionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isOauthUserRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer {
        $oauthUserRestrictionRequestTransfer->requireUser();

        foreach ($this->oauthUserRestrictionPlugins as $oauthUserRestrictionPlugin) {
            $oauthUserRestrictionResponseTransfer = $oauthUserRestrictionPlugin->isRestricted(
                $oauthUserRestrictionRequestTransfer
            );

            if ($oauthUserRestrictionResponseTransfer->getIsRestricted()) {
                return $oauthUserRestrictionResponseTransfer;
            }
        }

        return (new OauthUserRestrictionResponseTransfer())->setIsRestricted(false);
    }
}
