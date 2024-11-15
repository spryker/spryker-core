<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Plugin\CartReorderRestApi;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;
use Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin\RestCartReorderAttributesMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiFactory getFactory()
 */
class OrderAmendmentRestCartReorderAttributesMapperPlugin extends AbstractPlugin implements RestCartReorderAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps 'isAmendment' property from `RestCartReorderRequestAttributesTransfer` to `CartReorderRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function mapRestCartReorderRequestAttributesToCartReorderRequestTransfer(
        RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer,
        CartReorderRequestTransfer $cartReorderRequestTransfer
    ): CartReorderRequestTransfer {
        return $this->getFactory()
            ->createCartReorderRequestMapper()
            ->mapRestCartReorderAttributesToCartReorderRequestTransfer(
                $restCartReorderRequestAttributesTransfer,
                $cartReorderRequestTransfer,
            );
    }
}
