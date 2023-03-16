<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Plugin\GlueBackendApiApplicationAuthorizationConnector;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\AuthorizationRequestExpanderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiFactory getFactory()
 */
class WarehouseAuthorizationRequestExpanderPlugin extends AbstractPlugin implements AuthorizationRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `AuthorizationRequestTransfer.entity` with `GlueRequestWarehouseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function expand(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): AuthorizationRequestTransfer {
        return $this->getFactory()
            ->createWarehouseAuthorizationRequestExpander()
            ->expand(
                $authorizationRequestTransfer,
                $glueRequestTransfer,
            );
    }
}
