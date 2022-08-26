<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Communication\Plugin\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorConfig getConfig()
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Communication\GlueBackendApiApplicationAuthorizationConnectorCommunicationFactory getFactory()
 */
class ProtectedPathAuthorizationStrategyPlugin extends AbstractPlugin implements AuthorizationStrategyPluginInterface
{
    /**
     * @var string
     */
    protected const STRATEGY_NAME = 'ProtectedPath';

    /**
     * {@inheritDoc}
     * - Checks if the requested path is found by the fully qualified path name in an array of protected paths.
     * - Checks if the requested path is found by a regular expression in an array of protected paths.
     * - If the path is not found - it is not protected and does not require authorization.
     * - If found - it is validated and returns true if it has a valid token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFacade()->authorize($authorizationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getStrategyName(): string
    {
        return static::STRATEGY_NAME;
    }
}
