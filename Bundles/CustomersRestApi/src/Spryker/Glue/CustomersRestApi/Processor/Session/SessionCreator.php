<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class SessionCreator implements SessionCreatorInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var array|\Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[]
     */
    protected $customerExpanderPlugins;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerExpanderPluginInterface[] $customerExpanderPlugins
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        array $customerExpanderPlugins
    ) {
        $this->customerClient = $customerClient;
        $this->customerExpanderPlugins = $customerExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function setCustomer(RestRequestInterface $restRequest): void
    {
        $restUserTransfer = $restRequest->getRestUser();
        if (!$restUserTransfer) {
            return;
        }

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restUserTransfer->getSurrogateIdentifier() ? (int)$restUserTransfer->getSurrogateIdentifier() : null)
            ->setIsDirty(false)
            ->setCustomerReference($restUserTransfer->getNaturalIdentifier());

        $customerTransfer = $this->executeCustomerExpanderPlugins($restRequest, $customerTransfer);

        $this->customerClient
            ->setCustomerRawData($customerTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executeCustomerExpanderPlugins(RestRequestInterface $restRequest, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        foreach ($this->customerExpanderPlugins as $customerExpanderPlugin) {
            $customerTransfer = $customerExpanderPlugin->expand($customerTransfer, $restRequest);
        }

        return $customerTransfer;
    }
}
