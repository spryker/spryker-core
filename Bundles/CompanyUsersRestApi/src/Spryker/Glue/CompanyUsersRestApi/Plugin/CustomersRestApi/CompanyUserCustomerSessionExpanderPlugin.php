<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Plugin\CustomersRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerSessionExpanderPluginInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiFactory getFactory()
 */
class CompanyUserCustomerSessionExpanderPlugin extends AbstractPlugin implements CustomerSessionExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands customer transfer for session with company user transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expand(CustomerTransfer $customerTransfer, RestRequestInterface $restRequest): CustomerTransfer
    {
        return $this->getFactory()
            ->createCustomerSessionExpander()
            ->expand($customerTransfer, $restRequest);
    }
}
