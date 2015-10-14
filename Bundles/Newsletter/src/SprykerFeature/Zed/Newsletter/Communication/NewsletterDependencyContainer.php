<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Mail\Business\MailFacade;
use SprykerFeature\Zed\Newsletter\NewsletterConfig;
use SprykerFeature\Zed\Newsletter\NewsletterDependencyProvider;

/**
 * @method NewsletterConfig getConfig()
 */
class NewsletterDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return MailFacade
     */
    public function createMailFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return GlossaryFacade
     */
    public function createGlossaryFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_GLOSSARY);
    }

}
