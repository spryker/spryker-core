<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Persistence;

use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer getQueryContainer()
 */
class NewsletterPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery
     */
    public function createNewsletterSubscriberQuery()
    {
        return SpyNewsletterSubscriberQuery::create();
    }

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriptionQuery
     */
    public function createNewsletterSubscriptionQuery()
    {
        return SpyNewsletterSubscriptionQuery::create();
    }

    /**
     * @return \Orm\Zed\Newsletter\Persistence\SpyNewsletterTypeQuery
     */
    public function createNewsletterTypeQuery()
    {
        return SpyNewsletterTypeQuery::create();
    }

}
