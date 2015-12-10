<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business;

use SprykerEngine\Zed\Messenger\Business\MessengerFacade;
use SprykerFeature\Zed\Glossary\Business\Key\KeyManager;
use SprykerFeature\Zed\Glossary\Business\Translation\TranslationManager;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Glossary\Business\Key\KeyManagerInterface;
use SprykerFeature\Zed\Glossary\Business\Translation\TranslationManagerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\GlossaryConfig;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
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
        return new TranslationManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createKeyManager(),
            $this->getMessagesFacade()
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
     * @return MessengerFacade
     */
    protected function getMessagesFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::MESSAGES);
    }

    /**
     * @return KeyManagerInterface
     */
    public function createKeyManager()
    {
        return new KeyManager(
            $this->getQueryContainer()
        );
    }

}
