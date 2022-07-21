<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeFinderPluginInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class StorefrontScopeFinderPlugin extends AbstractPlugin implements ScopeFinderPluginInterface
{
    /**
     * @see \Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Uses as default plugin if no other available
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @return bool
     */
    public function isServing(OauthScopeFindTransfer $oauthScopeFindTransfer): bool
    {
        return $oauthScopeFindTransfer->getApplicationName() === static::GLUE_STOREFRONT_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Provides the set of OAuth scopes for storefront.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueStorefrontApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string
    {
        return $this->getFactory()->createStorefrontScopeFinder()->findScope($oauthScopeFindTransfer);
    }
}
