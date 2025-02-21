<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest\Widget;

use Generated\Shared\Transfer\CustomerDataChangeRequestConditionsTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestStatusEnum;
use Spryker\Shared\CustomerDataChangeRequest\Enum\CustomerDataChangeRequestTypeEnum;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Spryker\Yves\CustomerDataChangeRequest\CustomerDataChangeRequestFactory getFactory()
 * @method \Spryker\Yves\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 * @method \Spryker\Client\CustomerDataChangeRequest\CustomerDataChangeRequestClientInterface getClient()
 */
class CustomerEmailChangeRequestWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_CHANGE_REQUEST_ACTIVE_NAME = 'isChangeRequestActive';

    /**
     * @var string
     */
    protected const PARAMETER_EMAIL_NAME = 'email';

    /**
     * @var string
     */
    protected const PARAMETER_MINUTES_NAME = 'minutes';

    public function __construct()
    {
        $this->addIsChangeRequestActiveParameter();
        $this->addEmailParameter();
        $this->addMinutesParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerEmailChangeRequestWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerDataChangeRequest/views/customer-email-change-request/customer-email-change-request.twig';
    }

    /**
     * @return void
     */
    protected function addIsChangeRequestActiveParameter(): void
    {
        $pendingCustomerDataChangeRequestData = $this->getPendingCustomerDataChangeRequestData();

        $this->addParameter(
            static::PARAMETER_IS_CHANGE_REQUEST_ACTIVE_NAME,
            $pendingCustomerDataChangeRequestData !== null,
        );
    }

    /**
     * @return void
     */
    protected function addEmailParameter(): void
    {
        $pendingCustomerDataChangeRequestData = $this->getPendingCustomerDataChangeRequestData();

        $this->addParameter(
            static::PARAMETER_EMAIL_NAME,
            $pendingCustomerDataChangeRequestData ?? null,
        );
    }

    /**
     * @return void
     */
    protected function addMinutesParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_MINUTES_NAME,
            $this->getConfig()->getEmailChangeVerificationExpirationMinutes(),
        );
    }

    /**
     * @return string|null
     */
    protected function getPendingCustomerDataChangeRequestData(): ?string
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->findCustomerRawData();
        if (!$customerTransfer) {
            return null;
        }

        $customerDataChangeRequests = $this->getClient()->getCustomerDataChangeRequestCollection(
            (new CustomerDataChangeRequestCriteriaTransfer())
                ->setCustomerDataChangeRequestConditions(
                    (new CustomerDataChangeRequestConditionsTransfer())
                        ->addIdCustomer($customerTransfer->getIdCustomerOrFail())
                        ->addStatus(CustomerDataChangeRequestStatusEnum::PENDING->value)
                        ->addType(CustomerDataChangeRequestTypeEnum::EMAIL->value)
                        ->setIsExpired(false),
                ),
        )->getCustomerDataChangeRequests();

        return $customerDataChangeRequests->offsetExists(0) ? $customerDataChangeRequests->offsetGet(0)->getDataOrFail() : null;
    }
}
