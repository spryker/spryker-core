<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class CurrentStoreReferenceAccessTokenRequestExpanderPlugin extends AbstractPlugin implements AccessTokenRequestExpanderPluginInterface
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
     * @throws \Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        return $this->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);
    }
}
