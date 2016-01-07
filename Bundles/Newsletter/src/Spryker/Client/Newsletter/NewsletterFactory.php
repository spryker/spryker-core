<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Newsletter;

use Spryker\Client\Newsletter\Zed\NewsletterStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Newsletter\Zed\NewsletterStubInterface;

class NewsletterFactory extends AbstractFactory
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
