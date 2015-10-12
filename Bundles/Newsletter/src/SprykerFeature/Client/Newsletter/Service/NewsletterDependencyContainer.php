<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service;

use Generated\Client\Ide\FactoryAutoCompletion\NewsletterService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Newsletter\Service\Zed\NewsletterStubInterface;
use SprykerFeature\Client\Newsletter\NewsletterDependencyProvider;

/**
 * @method NewsletterService getFactory()
 */
class NewsletterDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return NewsletterStubInterface
     */
    public function createZedNewsletterStub()
    {
        return $this->getFactory()->createZedNewsletterStub(
            $this->getProvidedDependency(NewsletterDependencyProvider::SERVICE_ZED)
        );
    }

}
