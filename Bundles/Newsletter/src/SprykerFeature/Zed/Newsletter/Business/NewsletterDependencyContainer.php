<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\NewsletterBusiness;
use SprykerFeature\Zed\Newsletter\Business\Model\SubscriptionManagerInterface;
use SprykerFeature\Zed\Newsletter\NewsletterConfig;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * @method NewsletterBusiness getFactory()
 * @method NewsletterConfig getConfig()
 * @method NewsletterQueryContainer getQueryContainer()
 */
class NewsletterDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return SubscriptionManagerInterface
     */
    public function createSubscriptionManager()
    {
        return $this->getFactory()->createModelSubscriptionManager();
    }
}
