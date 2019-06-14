<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class RestRequestUpdater implements RestRequestUpdaterInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(CartsRestApiToPersistentCartClientInterface $persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function updateRestRequestWithAnonymousCustomerId(RestRequestInterface $restRequest): void
    {
        if ($restRequest->getRestUser()) {
            return;
        }

        $anonymousCustomerUniqueId = $restRequest->getHttpRequest()->headers
            ->get(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);
        if (empty($anonymousCustomerUniqueId)) {
            return;
        }

        $customerReference = $this->persistentCartClient->generateGuestCartCustomerReference($anonymousCustomerUniqueId);
        $restRequest->setRestUser((new RestUserTransfer())->setNaturalIdentifier($customerReference));
        $restRequest->setUser('', $customerReference);
    }
}
