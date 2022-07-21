<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeFinderPluginInterface;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class BackendScopeFinderPlugin extends AbstractPlugin implements ScopeFinderPluginInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Application\GlueBackendApiApplication::GLUE_BACKEND_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * {@inheritDoc}
     * - Checks if the host is being served by Backend API Application.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @return bool
     */
    public function isServing(OauthScopeFindTransfer $oauthScopeFindTransfer): bool
    {
        return $oauthScopeFindTransfer->getApplicationName() === static::GLUE_BACKEND_API_APPLICATION;
    }

    /**
     * {@inheritDoc}
     * - Finds scope by identifier.
     * - Returns identifier if scope exists, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueBackendApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string
    {
        return $this->getFactory()->createBackendScopeFinder()->findScope($oauthScopeFindTransfer);
    }
}
