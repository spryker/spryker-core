<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business;

use Spryker\Shared\Messenger\MessengerConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray;
use Spryker\Zed\Messenger\Business\Model\MessageTranslator;
use Spryker\Zed\Messenger\Business\Model\MessageTranslatorInterface;
use Spryker\Zed\Messenger\Business\Model\SessionMessageTray;
use Spryker\Zed\Messenger\MessengerDependencyProvider;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @method \Spryker\Zed\Messenger\MessengerConfig getConfig()
 */
class MessengerBusinessFactory extends AbstractBusinessFactory
{
    protected const DUMMY_LOCALE = '';

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\MessageTrayInterface
     */
    public function createMessageTray()
    {
        $messageTry = $this->getConfig()->getTray();
        if ($messageTry === MessengerConfig::IN_MEMORY_TRAY) {
            return $this->createInMemoryMessageTray();
        }

        return $this->createSessionMessageTray();
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\InMemoryMessageTray
     */
    public function createInMemoryMessageTray()
    {
        return new InMemoryMessageTray($this->createMessageTranslator());
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\SessionMessageTray
     */
    public function createSessionMessageTray()
    {
        return new SessionMessageTray($this->createMessageTranslator(), $this->getSession());
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\Model\MessageTranslatorInterface
     */
    public function createMessageTranslator(): MessageTranslatorInterface
    {
        return new MessageTranslator($this->getTranslationPlugins(), $this->createSymfonyTranslator());
    }

    /**
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    public function createSymfonyTranslator(): TranslatorInterface
    {
        return new Translator(static::DUMMY_LOCALE);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function getSession()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::SESSION);
    }

    /**
     * @deprecated See \Spryker\Zed\Messenger\Business\MessengerBusinessFactory::getTranslationPlugins
     *
     * @return \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface
     */
    public function getTranslationPlugin()
    {
        return $this->getProvidedDependency(MessengerDependencyProvider::PLUGIN_TRANSLATION);
    }

    /**
     * @return \Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface[]
     */
    public function getTranslationPlugins(): array
    {
        return array_merge(
            [$this->getTranslationPlugin()],
            $this->getProvidedDependency(MessengerDependencyProvider::PLUGINS_TRANSLATION)
        );
    }
}
