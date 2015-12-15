<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Newsletter;

use Spryker\Client\Newsletter\Zed\NewsletterStub;
use Spryker\Client\Kernel\AbstractDependencyContainer;
use Spryker\Client\Newsletter\Zed\NewsletterStubInterface;
use Spryker\Client\Newsletter\NewsletterDependencyProvider;

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
