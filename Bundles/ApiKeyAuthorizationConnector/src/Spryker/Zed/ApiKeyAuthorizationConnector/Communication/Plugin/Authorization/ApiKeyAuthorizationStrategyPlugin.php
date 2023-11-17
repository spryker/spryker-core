<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyAuthorizationConnector\Communication\Plugin\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\DetachedAuthorizationStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ApiKeyAuthorizationConnector\Business\ApiKeyAuthorizationConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorConfig getConfig()
 */
class ApiKeyAuthorizationStrategyPlugin extends AbstractPlugin implements AuthorizationStrategyPluginInterface, DetachedAuthorizationStrategyPluginInterface
{
    /**
     * @var string
     */
    protected const STRATEGY_NAME = 'ApiKey';

    /**
     * {@inheritDoc}
     * - Checks API Key identifier in the request.
     * - If an identifier is present - authorizes the request.
     * - If no identifier is present - the request is not authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFacade()
            ->authorize($authorizationRequestTransfer)
            ->getIsAuthorizedOrFail();
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
