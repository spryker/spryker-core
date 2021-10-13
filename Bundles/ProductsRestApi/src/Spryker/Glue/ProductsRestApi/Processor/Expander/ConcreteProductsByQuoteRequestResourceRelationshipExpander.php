<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ConcreteProductsByQuoteRequestResourceRelationshipExpander extends AbstractConcreteProductsResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return array<string>
     */
    protected function findProductConcreteSkusInAttributes(RestResourceInterface $restResource): array
    {
        /** @var \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer */
        $restQuoteRequestsAttributesTransfer = $restResource->getAttributes();
        $productConcreteSkus = [];
        if (!($restQuoteRequestsAttributesTransfer instanceof RestQuoteRequestsAttributesTransfer) && $restQuoteRequestsAttributesTransfer->getShownVersion()) {
            return [];
        }
        $restQuoteRequestsItemTransfer = $restQuoteRequestsAttributesTransfer->getShownVersion()->getCart()->getItems();
        foreach ($restQuoteRequestsItemTransfer as $restQuoteRequestItemTransfer) {
            if (!$restQuoteRequestItemTransfer->getSku()) {
                continue;
            }
            $productConcreteSkus[] = $restQuoteRequestItemTransfer->getSku();
        }

        return $productConcreteSkus;
    }
}
