<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\RestCartAttributesMapperPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiFactory getFactory()
 */
class OrderAmendmentRestCartAttributesMapperPlugin extends AbstractPlugin implements RestCartAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps field `amendmentOrderReference` from `QuoteTransfer` to `RestCartsAttributesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartsAttributesTransfer
     */
    public function mapQuoteTransferToRestCartAttributesTransfer(
        QuoteTransfer $quoteTransfer,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestCartsAttributesTransfer {
        return $this->getFactory()
            ->createRestCartAttributesMapper()
            ->mapQuoteTransferToRestCartAttributesTransfer($quoteTransfer, $restCartsAttributesTransfer);
    }
}
