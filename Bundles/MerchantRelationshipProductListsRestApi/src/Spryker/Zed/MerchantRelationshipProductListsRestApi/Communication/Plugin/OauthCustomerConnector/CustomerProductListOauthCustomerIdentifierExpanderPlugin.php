<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListsRestApi\Communication\Plugin\OauthCustomerConnector;

use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthCustomerConnectorExtension\Dependency\Plugin\OauthCustomerIdentifierExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListsRestApi\Business\MerchantRelationshipProductListsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipProductListsRestApi\MerchantRelationshipProductListsRestApiConfig getConfig()
 */
class CustomerProductListOauthCustomerIdentifierExpanderPlugin extends AbstractPlugin implements OauthCustomerIdentifierExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CustomerTransfer.customerProductListCollection` to be set.
     * - Expands `CustomerIdentifierTransfer` with customers product list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerIdentifierTransfer $customerIdentifierTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerIdentifierTransfer
     */
    public function expandCustomerIdentifier(
        CustomerIdentifierTransfer $customerIdentifierTransfer,
        CustomerTransfer $customerTransfer
    ): CustomerIdentifierTransfer {
        return $this->getFacade()
            ->expandCustomerIdentifierWithCustomerProductListCollection($customerIdentifierTransfer, $customerTransfer);
    }
}
