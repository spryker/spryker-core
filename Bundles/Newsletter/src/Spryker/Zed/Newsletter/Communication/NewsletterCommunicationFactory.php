<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;

/**
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer getQueryContainer()
 */
class NewsletterCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_GLOSSARY);
    }

}
