<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Mail\Business\MailFacade;
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
     * @return MailFacade
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return GlossaryFacade
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_GLOSSARY);
    }

}
