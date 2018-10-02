<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail;

use Spryker\Zed\Glossary\Communication\Plugin\TwigTranslatorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollection;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection;
use Spryker\Zed\Mail\Dependency\Facade\MailToGlossaryBridge;
use Spryker\Zed\Mail\Dependency\Mailer\MailToMailerBridge;
use Spryker\Zed\Mail\Dependency\Renderer\MailToRendererBridge;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;

class MailDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MAIL_PROVIDER_COLLECTION = 'mail provider collection';
    public const MAIL_TYPE_COLLECTION = 'mail collection';
    public const FACADE_GLOSSARY = 'glossary facade';
    public const RENDERER = 'twig';
    public const MAILER = 'mailer';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addMailProviderCollection($container);
        $container = $this->addMailCollection($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addRenderer($container);
        $container = $this->addMailer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailProviderCollection(Container $container)
    {
        $container[static::MAIL_PROVIDER_COLLECTION] = function () {
            $mailProviderCollection = $this->getMailProviderCollection();

            return $mailProviderCollection;
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionAddInterface
     */
    protected function getMailProviderCollection()
    {
        return new MailProviderCollection();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailCollection(Container $container)
    {
        $container[static::MAIL_TYPE_COLLECTION] = function () {
            $mailCollection = $this->getMailCollection();

            return $mailCollection;
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollectionAddInterface
     */
    protected function getMailCollection()
    {
        return new MailTypeCollection();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container)
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            $mailToGlossaryBridge = new MailToGlossaryBridge($container->getLocator()->glossary()->facade());

            return $mailToGlossaryBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRenderer(Container $container)
    {
        $container[static::RENDERER] = function () {
            $twig = $this->getTwigEnvironment();
            if (!$twig->hasExtension(TwigTranslatorPlugin::class)) {
                $translator = new TwigTranslatorPlugin();
                $twig->addExtension($translator);
            }
            $rendererBridge = new MailToRendererBridge($twig);

            return $rendererBridge;
        };

        return $container;
    }

    /**
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $pimplePlugin = new Pimple();
        $twig = $pimplePlugin->getApplication()['twig'];

        return $twig;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailer(Container $container)
    {
        $container[static::MAILER] = function () {
            $message = Swift_Message::newInstance();
            $transport = Swift_MailTransport::newInstance();
            $mailer = Swift_Mailer::newInstance($transport);

            $mailerBridge = new MailToMailerBridge($message, $mailer);

            return $mailerBridge;
        };

        return $container;
    }
}
