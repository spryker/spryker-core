<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business;

use Spryker\Zed\Glossary\Business\Internal\GlossaryInstaller;
use Spryker\Zed\Glossary\Business\Key\KeyManager;
use Spryker\Zed\Glossary\Business\Reader\TranslationReader;
use Spryker\Zed\Glossary\Business\Reader\TranslationReaderInterface;
use Spryker\Zed\Glossary\Business\Translation\TranslationManager;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface getRepository()
 */
class GlossaryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Glossary\Business\Translation\TranslationManagerInterface
     */
    public function createTranslationManager()
    {
        return new TranslationManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createKeyManager(),
            $this->getMessagesFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToMessengerInterface
     */
    protected function getMessagesFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\Key\KeyManagerInterface
     */
    public function createKeyManager()
    {
        return new KeyManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\Internal\GlossaryInstaller
     */
    public function createInstaller()
    {
        $installer = new GlossaryInstaller(
            $this->createTranslationManager(),
            $this->createKeyManager(),
            $this->getConfig()->getGlossaryFilePaths()
        );

        return $installer;
    }

    /**
     * @return \Spryker\Zed\Glossary\Business\Reader\TranslationReaderInterface
     */
    public function createTranslationReader(): TranslationReaderInterface
    {
        return new TranslationReader($this->getRepository());
    }
}
