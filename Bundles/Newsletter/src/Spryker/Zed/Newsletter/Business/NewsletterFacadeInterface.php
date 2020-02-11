<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;

interface NewsletterFacadeInterface
{
    /**
     * Specification:
     * - Identifies subscriber by provided subscriber email in a case insensitive way.
     * - Adds subscriber to each provided newsletter type:
     *      - Validates email.
     *      - Registers subscription if subscriber is not registered already.
     *      - Sends confirmation email.
     *      - Sets subscription as confirmed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * Specification:
     * - Identifies subscriber by provided subscriber email in a case insensitive way.
     * - Adds subscriber to each provided newsletter type:
     *      - Validates email.
     *      - Registers subscription if subscriber is not registered already.
     *      - Sends confirmation email.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * Specification:
     * - Confirms subscriber if subscriber was found by provided subscriber key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber);

    /**
     * Specification:
     * - Checks if the provided subscriber is subscribed to any of the provided newsletter type using case insensitive email matching.
     * - Returns with a list, each element contains the result for a newsletter type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest);

    /**
     * Specification:
     * - Unsubscribes provided subscriber from provided newsletter type list using case insensitive email matching.
     * - Sends unsubscribed mail for each newsletter type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest);

    /**
     * Specification:
     * - Finds subscriber by provided subscriber email.
     * - Connects subscriber to provided customer id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return bool
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber);

    /**
     * Specification:
     * - Adds newsletter types defined in configuration to persistent storage if no newsletter type is stored yet.
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification:
     * - Unsubscribes provided subscriber from provided newsletter types.
     * - Anonymizes personal information of the provided subscriber.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return void
     */
    public function anonymizeSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest);
}
