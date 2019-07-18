<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;

/**
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Newsletter\Business\NewsletterFacadeInterface getFacade()
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
