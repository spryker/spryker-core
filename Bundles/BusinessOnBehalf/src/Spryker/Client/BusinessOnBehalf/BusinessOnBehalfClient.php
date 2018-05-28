<?php

namespace Spryker\Client\BusinessOnBehalf;

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
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): array
    {
        return $this->getFactory()->createZedBusinessOnBehalfStub()->findActiveCompanyUsersByCustomerId($customerTransfer);
    }
}