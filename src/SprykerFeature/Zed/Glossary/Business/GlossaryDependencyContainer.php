<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryBusiness;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Glossary\Business\Key\KeyManagerInterface;
use SprykerFeature\Zed\Glossary\Business\Key\KeySourceInterface;
use SprykerFeature\Zed\Glossary\Business\Translation\TranslationManagerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use SprykerFeature\Zed\Glossary\GlossaryConfig;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @method GlossaryBusiness getFactory()
 * @method GlossaryConfig getConfig()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return TranslationManagerInterface
     */
    public function createTranslationManager()
    {
        return $this->getFactory()->createTranslationTranslationManager(
            $this->createGlossaryQueryContainer(),
            $this->createTouchFacade(),
            $this->createLocaleFacade(),
            $this->createKeyManager(),
            $this->getLocator()
        );
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    protected function createGlossaryQueryContainer()
    {
        return $this->getLocator()->glossary()->queryContainer();
    }

    /**
     * @return GlossaryToTouchInterface
     */
    protected function createTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return KeyManagerInterface
     */
    public function createKeyManager()
    {
        return $this->getFactory()->createKeyKeyManager(
            $this->createKeySource(),
            $this->createGlossaryQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return KeySourceInterface
     */
    protected function createKeySource()
    {
        return $this->getFactory()->createKeyFileKeySource(
            $this->getConfig()->getGlossaryKeyFileName()
        );
    }
}
