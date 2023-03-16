<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Expander;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;

class WarehouseAuthorizationRequestExpander implements WarehouseAuthorizationRequestExpanderInterface
{
    /**
     * @var string
     */
    protected const AUTHORIZATION_REQUEST_ENTITY_DATA_WAREHOUSE_KEY = 'glueRequestWarehouse';

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function expand(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): AuthorizationRequestTransfer {
        $data = $authorizationRequestTransfer->getEntityOrFail()->getData();
        $data[static::AUTHORIZATION_REQUEST_ENTITY_DATA_WAREHOUSE_KEY] = $glueRequestTransfer->getRequestWarehouse();

        $authorizationRequestTransfer->getEntityOrFail()->setData($data);

        return $authorizationRequestTransfer;
    }
}
