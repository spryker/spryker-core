<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Activator\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\MultiFactorAuth\Controller\CustomerMultiFactorAuthFlowController;
use Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomerMultiFactorAuthActivator implements CustomerMultiFactorAuthActivatorInterface
{
    /**
     * @var string
     */
    protected const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $client
     * @param \Spryker\Yves\MultiFactorAuth\Reader\Request\RequestReaderInterface $requestReader
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $client,
        protected RequestReaderInterface $requestReader
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function activate(Request $request, CustomerTransfer $customerTransfer): void
    {
        $isActivation = $this->requestReader->get($request, CustomerMultiFactorAuthFlowController::IS_ACTIVATION);

        $status = $isActivation ? MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION : MultiFactorAuthConstants::STATUS_ACTIVE;
        $type = $isActivation ? $this->requestReader->get($request, static::TYPE_TO_SET_UP) : $request->query->get(static::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setStatus($status)
            ->setType($type);

        $this->client->activateCustomerMultiFactorAuth($multiFactorAuthTransfer);
    }
}
