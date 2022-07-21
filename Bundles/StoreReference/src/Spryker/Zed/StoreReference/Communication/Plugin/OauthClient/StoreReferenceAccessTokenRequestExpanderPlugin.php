<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreReference\StoreReferenceConfig getConfig()
 */
class StoreReferenceAccessTokenRequestExpanderPlugin extends AbstractPlugin implements AccessTokenRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds a store reference for currently selected store.
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` with found store reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        return $this->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);
    }
}
