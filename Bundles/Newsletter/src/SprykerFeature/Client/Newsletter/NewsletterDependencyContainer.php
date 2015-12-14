<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter;

use SprykerFeature\Client\Newsletter\Zed\NewsletterStub;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Newsletter\Zed\NewsletterStubInterface;
use SprykerFeature\Client\Newsletter\NewsletterDependencyProvider;

class NewsletterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return NewsletterStubInterface
     */
    public function createZedNewsletterStub()
    {
        return new NewsletterStub(
            $this->getProvidedDependency(NewsletterDependencyProvider::SERVICE_ZED)
        );
    }

}
