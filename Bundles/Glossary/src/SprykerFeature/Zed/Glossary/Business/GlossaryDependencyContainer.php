<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Glossary\Business\Key\KeyManagerInterface;
use SprykerFeature\Zed\Glossary\Business\Key\KeySourceInterface;
use SprykerFeature\Zed\Glossary\Business\Translation\TranslationManagerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\GlossaryConfig;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @method GlossaryBusiness getFactory()
 * @method GlossaryConfig getConfig()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class GlossaryDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return TranslationManagerInterface
     */
    public function createTranslationManager()
    {
        return $this->getFactory()->createTranslationTranslationManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createKeyManager()
        );
    }

    /**
     * @return GlossaryToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return KeyManagerInterface
     */
    public function createKeyManager()
    {
        return $this->getFactory()->createKeyKeyManager(
            $this->createKeySource(),
            $this->getQueryContainer()
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
