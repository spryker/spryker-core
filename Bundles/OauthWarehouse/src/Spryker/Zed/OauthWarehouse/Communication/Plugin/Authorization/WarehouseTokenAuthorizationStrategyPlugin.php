<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Communication\Plugin\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 * @method \Spryker\Zed\OauthWarehouse\Business\OauthWarehouseFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthWarehouse\Communication\OauthWarehouseCommunicationFactory getFactory()
 */
class WarehouseTokenAuthorizationStrategyPlugin extends AbstractPlugin implements AuthorizationStrategyPluginInterface
{
    /**
     * @var string
     */
    protected const STRATEGY_NAME = 'WarehouseTokenAuthorizationStrategy';

    /**
     * {@inheritDoc}
     * - Returns true if the request identity is user.
     * - Returns true if the request identity is warehouse, and it's valid.
     * - Returns false in other cases.
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
