<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestExpander implements RequestExpanderInterface
{
    /**
     * @var string
     */
    protected const REQUEST_KEY_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected CartsRestApiToPersistentCartClientInterface $persistentCartClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(CartsRestApiToPersistentCartClientInterface $persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function expandRequestWithCustomerReference(Request $request): void
    {
        if ($request->request->get(static::REQUEST_KEY_CUSTOMER_REFERENCE)) {
            return;
        }

        $anonymousCustomerUniqueId = $request->headers->get(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);
        if (!$anonymousCustomerUniqueId) {
            return;
        }

        $request->request->set(
            static::REQUEST_KEY_CUSTOMER_REFERENCE,
            $this->persistentCartClient->generateGuestCartCustomerReference($anonymousCustomerUniqueId),
        );
    }
}
