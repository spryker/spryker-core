<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder;
use Spryker\Zed\Mail\Business\Model\Mailer\MailHandler;
use Spryker\Zed\Mail\Business\Model\Provider\SwiftMailer;
use Spryker\Zed\Mail\Business\Model\Renderer\TwigRenderer;
use Spryker\Zed\Mail\MailDependencyProvider;

/**
 * @method \Spryker\Zed\Mail\MailConfig getConfig()
 */
class MailBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mailer\MailHandlerInterface
     */
    public function createMailHandler()
    {
        return new MailHandler(
            $this->createMailBuilder(),
            $this->getMailTypeCollection(),
            $this->getMailProviderCollection()
        );
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilder
     */
    protected function createMailBuilder()
    {
        return new MailBuilder(
            $this->getGlossaryFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface
     */
    protected function getMailProviderCollection()
    {
        return $this->getProvidedDependency(MailDependencyProvider::MAIL_PROVIDER_COLLECTION);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionGetInterface
     */
    protected function getMailTypeCollection()
    {
        return $this->getProvidedDependency(MailDependencyProvider::MAIL_TYPE_COLLECTION);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\SwiftMailer
     */
    public function createMailer()
    {
        return new SwiftMailer(
            $this->createRenderer(),
            $this->getMailer()
        );
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Renderer\TwigRenderer
     */
    public function createRenderer()
    {
        return new TwigRenderer($this->getRenderer());
    }

    /**
     * @return \Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(MailDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\Mail\Dependency\Renderer\MailToRendererInterface
     */
    protected function getRenderer()
    {
        return $this->getProvidedDependency(MailDependencyProvider::RENDERER);
    }

    /**
     * @return \Spryker\Zed\Mail\Dependency\Mailer\MailToMailerInterface
     */
    protected function getMailer()
    {
        return $this->getProvidedDependency(MailDependencyProvider::MAILER);
    }
}
