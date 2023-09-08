<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantRelationshipProductListsRestApi\Plugin\CustomersRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\MerchantRelationshipProductListsRestApi\MerchantRelationshipProductListsRestApiFactory getFactory()
 */
class CustomerProductListCustomerExpanderPlugin extends AbstractPlugin implements CustomerExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `RestRequest.restUser` to be set.
     * - Does nothing if `RestRequest.restUser` is not set.
     * - Expands `CustomerTransfer` with customer's product list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expand(
        CustomerTransfer $customerTransfer,
        RestRequestInterface $restRequest
    ): CustomerTransfer {
        return $this->getFactory()
            ->createCustomerExpander()
            ->expandCustomerWithCustomerProductListCollection($customerTransfer, $restRequest);
    }
}
