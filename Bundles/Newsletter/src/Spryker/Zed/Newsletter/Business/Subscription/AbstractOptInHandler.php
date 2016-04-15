<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

abstract class AbstractOptInHandler
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface
     */
    protected $subscriberKeyGenerator;

    /**
     * @var \Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInSenderInterface[]
     */
    protected $subscriberOptInSenders;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface $subscriberKeyGenerator
     */
    public function __construct(NewsletterQueryContainerInterface $queryContainer, SubscriberKeyGeneratorInterface $subscriberKeyGenerator)
    {
        $this->queryContainer = $queryContainer;
        $this->subscriberKeyGenerator = $subscriberKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $subscriberTransfer
     *
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriber
     */
    protected function findSubscriberEntity(NewsletterSubscriberTransfer $subscriberTransfer)
    {
        $subscriberQuery = $this->queryContainer->querySubscriber();

        $idNewsletterSubscriber = $subscriberTransfer->getIdNewsletterSubscriber();
        if ($idNewsletterSubscriber !== null) {
            return $subscriberQuery->findOneByIdNewsletterSubscriber($idNewsletterSubscriber);
        }

        $email = $subscriberTransfer->getEmail();
        if ($email !== null) {
            return $subscriberQuery->findOneByEmail($email);
        }

        return null;
    }

}
