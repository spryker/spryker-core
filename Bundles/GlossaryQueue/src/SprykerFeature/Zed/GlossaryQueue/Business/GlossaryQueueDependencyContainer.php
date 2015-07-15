<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryQueueBusiness;
use SprykerFeature\Zed\GlossaryQueue\Business\Model\QueueTranslationManagerInterface;
use SprykerFeature\Zed\GlossaryQueue\Dependency\Facade\GlossaryQueueToGlossaryInterface;
use SprykerFeature\Zed\GlossaryQueue\GlossaryQueueDependencyProvider;

/**
 * @method GlossaryQueueBusiness getFactory()
 */
class GlossaryQueueDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return QueueTranslationManagerInterface
     */
    public function createQueueTranslationManager()
    {
        return $this->getFactory()->createModelQueueTranslationManager(
            $this->getGlossaryFacade()
        );
    }

    /**
     * @throws \ErrorException
     *
     * @return GlossaryQueueToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(GlossaryQueueDependencyProvider::GLOSSARY_FACADE);
    }

}
