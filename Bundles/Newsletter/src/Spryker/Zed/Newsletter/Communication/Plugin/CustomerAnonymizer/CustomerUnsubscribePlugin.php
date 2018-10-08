<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Communication\Plugin\CustomerAnonymizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Newsletter\Business\NewsletterFacadeInterface getFacade()
 * @method \Spryker\Zed\Newsletter\Business\NewsletterFacadeInterface getQueryContainer()
 * @method \Spryker\Zed\Newsletter\Communication\NewsletterCommunicationFactory getFactory()
 */
class CustomerUnsubscribePlugin extends AbstractPlugin implements CustomerAnonymizerPluginInterface
{
    /**
     * @var array
     */
    protected $newsletterTypes;

    /**
     * @api
     *
     * @param array $newsletterTypes
     */
    public function __construct(array $newsletterTypes)
    {
        $this->newsletterTypes = $newsletterTypes;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        $subscriptionRequestTransfer = $this->createSubscriptionRequest($customerTransfer);

        $this->getFacade()->anonymizeSubscription($subscriptionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer
     */
    protected function createSubscriptionRequest(CustomerTransfer $customerTransfer)
    {
        $subscriberTransfer = new NewsletterSubscriberTransfer();
        $subscriberTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $subscriberTransfer->setEmail($customerTransfer->getEmail());

        $subscriptionRequestTransfer = new NewsletterSubscriptionRequestTransfer();
        $subscriptionRequestTransfer->setNewsletterSubscriber($subscriberTransfer);

        foreach ($this->newsletterTypes as $newsletterTypeName) {
            $newsletterTypeTransfer = new NewsletterTypeTransfer();
            $newsletterTypeTransfer->setName($newsletterTypeName);

            $subscriptionRequestTransfer->addSubscriptionType($newsletterTypeTransfer);
        }

        return $subscriptionRequestTransfer;
    }
}
