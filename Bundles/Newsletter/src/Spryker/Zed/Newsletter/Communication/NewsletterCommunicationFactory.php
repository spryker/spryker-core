<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToGlossaryInterface;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailInterface;
use Spryker\Zed\Newsletter\NewsletterConfig;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;

/**
 * @method NewsletterConfig getConfig()
 * @method NewsletterQueryContainer getQueryContainer()
 */
class NewsletterCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return NewsletterToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return NewsletterToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_GLOSSARY);
    }

}
