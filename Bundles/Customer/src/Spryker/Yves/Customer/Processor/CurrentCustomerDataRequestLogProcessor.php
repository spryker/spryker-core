<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Customer\Processor;

use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CurrentCustomerDataRequestLogProcessor implements CurrentCustomerDataRequestLogProcessorInterface
{
    /**
     * @var string
     */
    protected const RECORD_KEY_EXTRA = 'extra';

    /**
     * @var string
     */
    protected const RECORD_KEY_REQUEST = 'request';

    /**
     * @uses \Spryker\Client\Customer\Session\CustomerSession::SESSION_KEY
     *
     * @var string
     */
    protected const SESSION_KEY_CUSTOMER_DATA = 'customer data';

    /**
     * @var string
     */
    protected const RECORD_KEY_USERNAME = 'username';

    /**
     * @var string
     */
    protected const RECORD_KEY_CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected RequestStack $requestStack;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        $customerTransfer = $this->findCurrentCustomer();

        if (!$customerTransfer) {
            return $data;
        }

        $currentRequestData = $this->getCurrentRequestData($customerTransfer);

        if (isset($data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST])) {
            $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = array_merge(
                $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST],
                $currentRequestData,
            );

            return $data;
        }

        $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_REQUEST] = $currentRequestData;

        return $data;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCurrentCustomer(): ?CustomerTransfer
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (!$currentRequest || !$currentRequest->hasSession()) {
            return null;
        }

        return $this->findCustomerInRequest($currentRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array<string, mixed>
     */
    protected function getCurrentRequestData(CustomerTransfer $customerTransfer): array
    {
        $currentRequestData = [];

        $currentRequestData[static::RECORD_KEY_USERNAME] = $customerTransfer->getEmail();
        $currentRequestData[static::RECORD_KEY_CUSTOMER_REFERENCE] = $customerTransfer->getCustomerReference();

        return $currentRequestData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerInRequest(Request $request): ?CustomerTransfer
    {
        return $request->getSession()->get(static::SESSION_KEY_CUSTOMER_DATA);
    }
}
