<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service;

use SprykerFeature\Client\Newsletter\Service\Zed\NewsletterStub;
use Generated\Client\Ide\FactoryAutoCompletion\NewsletterService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Newsletter\Service\Zed\NewsletterStubInterface;
use SprykerFeature\Client\Newsletter\NewsletterDependencyProvider;

class NewsletterDependencyContainer extends AbstractServiceDependencyContainer
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
