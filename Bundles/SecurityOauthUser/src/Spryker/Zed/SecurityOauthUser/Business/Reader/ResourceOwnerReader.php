<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Reader;

use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;

class ResourceOwnerReader implements ResourceOwnerReaderInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface[]
     */
    protected $oauthUserClientStrategyPlugins;

    /**
     * @param \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface[] $oauthUserClientStrategyPlugins
     */
    public function __construct(array $oauthUserClientStrategyPlugins)
    {
        $this->oauthUserClientStrategyPlugins = $oauthUserClientStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerResponseTransfer
     */
    public function getResourceOwner(
        ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
    ): ResourceOwnerResponseTransfer {
        $resourceOwnerRequestTransfer
            ->requireCode()
            ->requireState();

        foreach ($this->oauthUserClientStrategyPlugins as $oauthUserClientStrategyPlugin) {
            if ($oauthUserClientStrategyPlugin->isApplicable($resourceOwnerRequestTransfer)) {
                return $oauthUserClientStrategyPlugin->getResourceOwner($resourceOwnerRequestTransfer);
            }
        }

        return (new ResourceOwnerResponseTransfer())->setIsSuccessful(false);
    }
}
