<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Controller;

use Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer;
use Spryker\Glue\GlueApplication\Controller\AbstractRestController;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class CustomersResourceController extends AbstractRestController
{
    /**
     * @param \Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer): RestResponseInterface
    {
        return $this->getFactory()->createCustomersWriter()->registerCustomer($restRegisterCustomerAttributesTransfer);
    }
}
