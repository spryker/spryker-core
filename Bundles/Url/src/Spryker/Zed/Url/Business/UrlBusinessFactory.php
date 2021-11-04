<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Url\Business\Deletion\AbstractUrlDeleterSubject;
use Spryker\Zed\Url\Business\Deletion\Observer\UrlDeletePluginObserver;
use Spryker\Zed\Url\Business\Deletion\UrlDeleter;
use Spryker\Zed\Url\Business\Redirect\Observer\UrlRedirectAppendObserver;
use Spryker\Zed\Url\Business\Redirect\Observer\UrlRedirectInjectionObserver;
use Spryker\Zed\Url\Business\Redirect\Observer\UrlRedirectOverwriteObserver;
use Spryker\Zed\Url\Business\Redirect\Observer\UrlRedirectUpdateObserver;
use Spryker\Zed\Url\Business\Redirect\Observer\UrlUpdateObserver;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectActivator;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectCreator;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectReader;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectUpdater;
use Spryker\Zed\Url\Business\Redirect\UrlRedirectValidator;
use Spryker\Zed\Url\Business\Url\AbstractUrlCreatorSubject;
use Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterSubject;
use Spryker\Zed\Url\Business\Url\Observer\UrlCreatePluginObserver;
use Spryker\Zed\Url\Business\Url\Observer\UrlUpdatePluginObserver;
use Spryker\Zed\Url\Business\Url\UrlActivator;
use Spryker\Zed\Url\Business\Url\UrlCreator;
use Spryker\Zed\Url\Business\Url\UrlReader;
use Spryker\Zed\Url\Business\Url\UrlUpdater;
use Spryker\Zed\Url\UrlDependencyProvider;

/**
 * @method \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Url\UrlConfig getConfig()
 * @method \Spryker\Zed\Url\Persistence\UrlRepositoryInterface getRepository()
 */
class UrlBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorInterface
     */
    public function createUrlCreator()
    {
        $urlCreator = new UrlCreator($this->getQueryContainer(), $this->createUrlReader(), $this->createUrlActivator());

        $this->attachUrlCreatorObservers($urlCreator);

        return $urlCreator;
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlUpdaterInterface
     */
    public function createUrlUpdater()
    {
        $urlUpdater = new UrlUpdater($this->getQueryContainer(), $this->createUrlReader(), $this->createUrlActivator());

        $this->attachUrlUpdaterObservers($urlUpdater);

        return $urlUpdater;
    }

    /**
     * @return \Spryker\Zed\Url\Business\Deletion\UrlDeleterInterface
     */
    public function createUrlDeleter()
    {
        $urlDeleter = new UrlDeleter($this->getQueryContainer(), $this->createUrlActivator(), $this->createUrlRedirectActivator());

        $this->attachUrlDeleterObservers($urlDeleter);

        return $urlDeleter;
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlReaderInterface
     */
    public function createUrlReader()
    {
        return new UrlReader(
            $this->getQueryContainer(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlActivatorInterface
     */
    public function createUrlActivator()
    {
        return new UrlActivator($this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Redirect\UrlRedirectCreatorInterface
     */
    public function createUrlRedirectCreator()
    {
        return new UrlRedirectCreator($this->getQueryContainer(), $this->createUrlCreator(), $this->createUrlRedirectActivator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Redirect\UrlRedirectUpdaterInterface
     */
    public function createUrlRedirectUpdater()
    {
        return new UrlRedirectUpdater($this->getQueryContainer(), $this->createUrlUpdater(), $this->createUrlRedirectActivator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Redirect\UrlRedirectReaderInterface
     */
    public function createUrlRedirectReader()
    {
        return new UrlRedirectReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Redirect\UrlRedirectActivatorInterface
     */
    public function createUrlRedirectActivator()
    {
        return new UrlRedirectActivator($this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Redirect\UrlRedirectValidatorInterface
     */
    public function createUrlRedirectValidator()
    {
        return new UrlRedirectValidator($this->getQueryContainer());
    }

    /**
     * @deprecated Use business classes from Spryker\Zed\Url\Business\Url namespace.
     *
     * @return \Spryker\Zed\Url\Business\UrlManagerInterface
     */
    public function createUrlManager()
    {
        return new UrlManager(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getTouchFacade(),
            $this->getProvidedDependency(UrlDependencyProvider::PLUGIN_PROPEL_CONNECTION),
        );
    }

    /**
     * @deprecated Use business classes from Spryker\Zed\Url\Business\Redirect namespace.
     *
     * @return \Spryker\Zed\Url\Business\RedirectManagerInterface
     */
    public function createRedirectManager()
    {
        return new RedirectManager(
            $this->getQueryContainer(),
            $this->createUrlManager(),
            $this->getTouchFacade(),
            $this->getProvidedDependency(UrlDependencyProvider::PLUGIN_PROPEL_CONNECTION),
        );
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\UrlToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Url\Dependency\UrlToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlCreatorSubject $urlCreator
     *
     * @return void
     */
    protected function attachUrlCreatorObservers(AbstractUrlCreatorSubject $urlCreator)
    {
        foreach ($this->createUrlCreatorBeforeSaveObservers() as $urlWriterObserver) {
            $urlCreator->attachBeforeSaveObserver($urlWriterObserver);
        }

        foreach ($this->createUrlCreatorAfterSaveObservers() as $urlWriterObserver) {
            $urlCreator->attachAfterSaveObserver($urlWriterObserver);
        }
    }

    /**
     * @param \Spryker\Zed\Url\Business\Url\AbstractUrlUpdaterSubject $urlUpdater
     *
     * @return void
     */
    protected function attachUrlUpdaterObservers(AbstractUrlUpdaterSubject $urlUpdater)
    {
        foreach ($this->createUrlUpdaterBeforeSaveObservers() as $urlUpdaterObserver) {
            $urlUpdater->attachBeforeSaveObserver($urlUpdaterObserver);
        }

        foreach ($this->createUrlUpdaterAfterSaveObservers() as $urlUpdaterObserver) {
            $urlUpdater->attachAfterSaveObserver($urlUpdaterObserver);
        }
    }

    /**
     * @param \Spryker\Zed\Url\Business\Deletion\AbstractUrlDeleterSubject $urlDeleter
     *
     * @return void
     */
    protected function attachUrlDeleterObservers(AbstractUrlDeleterSubject $urlDeleter)
    {
        foreach ($this->createUrlDeleterBeforeDeleteObservers() as $urlUpdaterObserver) {
            $urlDeleter->attachBeforeDeleteObserver($urlUpdaterObserver);
        }

        foreach ($this->createUrlDeleterAfterDeleteObservers() as $urlUpdaterObserver) {
            $urlDeleter->attachAfterDeleteObserver($urlUpdaterObserver);
        }
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface>
     */
    protected function createUrlCreatorBeforeSaveObservers()
    {
        return [
            $this->createUrlRedirectOverwriteObserver(),
            $this->createUrlBeforeCreatePluginObserver(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface>
     */
    protected function createUrlCreatorAfterSaveObservers()
    {
        return [
            $this->createUrlRedirectAppendObserver(),
            $this->createUrlRedirectInjectionObserver(),
            $this->createUrlAfterCreatePluginObserver(),
        ];
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface
     */
    protected function createUrlRedirectInjectionObserver()
    {
        return new UrlRedirectInjectionObserver($this->getQueryContainer(), $this->createUrlRedirectActivator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface|\Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface
     */
    protected function createUrlRedirectAppendObserver()
    {
        return new UrlRedirectAppendObserver($this->getQueryContainer(), $this->createUrlRedirectActivator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface|\Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface
     */
    protected function createUrlRedirectOverwriteObserver()
    {
        return new UrlRedirectOverwriteObserver($this->getQueryContainer(), $this->createUrlDeleter());
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface>
     */
    protected function createUrlUpdaterBeforeSaveObservers()
    {
        return [
            $this->createUrlRedirectOverwriteObserver(),
            $this->createUrlBeforeUpdatePluginObserver(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface>
     */
    protected function createUrlUpdaterAfterSaveObservers()
    {
        /** @var array<\Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface> $observers */
        $observers = [
            $this->createUrlRedirectUpdateObserver(),
            $this->createUrlRedirectInjectionObserver(),
            $this->createUrlRedirectAppendObserver(),
            $this->createUrlUpdateObserver(),
            $this->createUrlAfterUpdatePluginObserver(),
        ];

        return $observers;
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Deletion\UrlDeleterBeforeDeleteObserverInterface>
     */
    protected function createUrlDeleterBeforeDeleteObservers()
    {
        return [
            $this->createUrlBeforeDeletePluginObserver(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Url\Business\Deletion\UrlDeleterAfterDeleteObserverInterface>
     */
    protected function createUrlDeleterAfterDeleteObservers()
    {
        return [
            $this->createUrlAfterDeletePluginObserver(),
        ];
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface
     */
    protected function createUrlRedirectUpdateObserver()
    {
        return new UrlRedirectUpdateObserver($this->getQueryContainer(), $this->createUrlRedirectActivator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface
     */
    protected function createUrlUpdateObserver()
    {
        return new UrlUpdateObserver($this->createUrlRedirectCreator());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorBeforeSaveObserverInterface
     */
    protected function createUrlBeforeCreatePluginObserver()
    {
        return new UrlCreatePluginObserver($this->getUrlBeforeCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlCreatorAfterSaveObserverInterface
     */
    protected function createUrlAfterCreatePluginObserver()
    {
        return new UrlCreatePluginObserver($this->getUrlAfterCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlUpdaterBeforeSaveObserverInterface
     */
    protected function createUrlBeforeUpdatePluginObserver()
    {
        return new UrlUpdatePluginObserver($this->getUrlBeforeUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Url\UrlUpdaterAfterSaveObserverInterface
     */
    protected function createUrlAfterUpdatePluginObserver()
    {
        return new UrlUpdatePluginObserver($this->getUrlAfterUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Deletion\UrlDeleterBeforeDeleteObserverInterface
     */
    protected function createUrlBeforeDeletePluginObserver()
    {
        return new UrlDeletePluginObserver($this->getUrlBeforeDeletePlugins());
    }

    /**
     * @return \Spryker\Zed\Url\Business\Deletion\UrlDeleterAfterDeleteObserverInterface
     */
    protected function createUrlAfterDeletePluginObserver()
    {
        return new UrlDeletePluginObserver($this->getUrlAfterDeletePlugins());
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface>
     */
    protected function getUrlBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_BEFORE_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlCreatePluginInterface>
     */
    protected function getUrlAfterCreatePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_AFTER_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface>
     */
    protected function getUrlBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_BEFORE_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlUpdatePluginInterface>
     */
    protected function getUrlAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_AFTER_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface>
     */
    protected function getUrlBeforeDeletePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_BEFORE_DELETE);
    }

    /**
     * @return array<\Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface>
     */
    protected function getUrlAfterDeletePlugins()
    {
        return $this->getProvidedDependency(UrlDependencyProvider::PLUGINS_URL_AFTER_DELETE);
    }
}
