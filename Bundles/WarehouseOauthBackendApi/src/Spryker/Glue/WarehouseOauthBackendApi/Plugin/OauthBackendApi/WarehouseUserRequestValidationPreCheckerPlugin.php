<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Plugin\OauthBackendApi;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\OauthBackendApiExtension\Dependency\Plugin\UserRequestValidationPreCheckerPluginInterface;

/**
 * @method \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiFactory getFactory()
 */
class WarehouseUserRequestValidationPreCheckerPlugin extends AbstractPlugin implements UserRequestValidationPreCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `GlueRequestWarehouseTransfer` is included in `GlueRequestTransfer`.
     * - If `GlueRequestWarehouseTransfer` is included, `GlueRequestValidationTransfer` is set as valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\GlueRequestValidationTransfer $glueRequestValidationTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function preCheck(
        GlueRequestTransfer $glueRequestTransfer,
        GlueRequestValidationTransfer $glueRequestValidationTransfer
    ): GlueRequestValidationTransfer {
        return $this->getFactory()
            ->createWarehouseUserRequestValidator()
            ->preCheck(
                $glueRequestTransfer,
                $glueRequestValidationTransfer,
            );
    }
}
