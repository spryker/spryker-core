<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\BusinessOnBehalf\BusinessOnBehalfFactory getFactory()
 */
class BusinessOnBehalfClient extends AbstractClient implements BusinessOnBehalfClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFactory()->createZedBusinessOnBehalfStub()->findActiveCompanyUsersByCustomerId($customerTransfer);
    }
}
