<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Sender;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;

class RequestStatusChangeMailNotificationSender implements RequestStatusChangeMailNotificationSenderInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Communication\Plugin\Mail\MerchantRelationRequestStatusChangeMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'merchant relation request status change';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface
     */
    protected MerchantRelationRequestToMailFacadeInterface $mailFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig
     */
    protected MerchantRelationRequestConfig $merchantRelationRequestConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     */
    public function __construct(
        MerchantRelationRequestToMailFacadeInterface $mailFacade,
        MerchantRelationRequestConfig $merchantRelationRequestConfig
    ) {
        $this->mailFacade = $mailFacade;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function sendRequestStatusChangeMailNotification(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        $merchantRelationRequestTransfers = $merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests();
        foreach ($merchantRelationRequestTransfers as $merchantRelationRequestTransfer) {
            if ($this->isMailNotificationNeeded($merchantRelationRequestTransfer)) {
                $this->handleMail($merchantRelationRequestTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isMailNotificationNeeded(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return in_array(
            $merchantRelationRequestTransfer->getStatus(),
            $this->merchantRelationRequestConfig->getApplicableForRequestStatusChangeMailNotificationStatuses(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return void
     */
    protected function handleMail(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): void
    {
        $this->mailFacade->handleMail(
            (new MailTransfer())
                ->setType(static::MAIL_TYPE)
                ->setCustomer($merchantRelationRequestTransfer->getCompanyUserOrFail()->getCustomerOrFail())
                ->setMerchantRelationRequest($merchantRelationRequestTransfer)
                ->setMerchantRelationRequestLink($this->getMerchantRelationRequestLink($merchantRelationRequestTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return string
     */
    protected function getMerchantRelationRequestLink(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): string {
        return sprintf(
            '%s%s/%s',
            $this->merchantRelationRequestConfig->getYvesBaseUrl(),
            $this->merchantRelationRequestConfig->getMerchantRelationRequestPath(),
            $merchantRelationRequestTransfer->getUuidOrFail(),
        );
    }
}
