<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerSessionWriter implements CustomerSessionWriterInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var array|\Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerSessionExpanderPluginInterface[]
     */
    protected $customerSessionExpanderPlugins;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerSessionExpanderPluginInterface[] $customerSessionExpanderPlugins
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        array $customerSessionExpanderPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->customerSessionExpanderPlugins = $customerSessionExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function setCustomerSession(RestRequestInterface $restRequest): void
    {
        if (!$restRequest->getUser()) {
            return;
        }

        $user = $restRequest->getUser();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($user->getSurrogateIdentifier() ? (int)$user->getSurrogateIdentifier() : null)
            ->setIsDirty(false)
            ->setCustomerReference($user->getNaturalIdentifier());

        $customerTransfer = $this->executeCustomerSessionExpanderPlugins($restRequest, $customerTransfer);

        $this->customerClient
            ->setCustomer($customerTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerSessionExpanderPlugins(RestRequestInterface $restRequest, CustomerTransfer $customerTransfer): \Generated\Shared\Transfer\CustomerTransfer
    {
        foreach ($this->customerSessionExpanderPlugins as $customerSessionExpanderPlugin) {
            $customerTransfer = $customerSessionExpanderPlugin->expand($customerTransfer, $restRequest);
        }

        return $customerTransfer;
    }
}
