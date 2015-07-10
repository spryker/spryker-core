<?php

namespace SprykerFeature\Zed\GlossaryQueue\Communication\Plugin;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\GlossaryQueue\Business\GlossaryQueueFacade;
use SprykerFeature\Zed\GlossaryQueue\Communication\GlossaryQueueDependencyContainer;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

/**
 * @method GlossaryQueueDependencyContainer getDependencyContainer()
 * @method GlossaryQueueFacade getFacade()
 */
class GlossaryTaskWorkerPlugin extends AbstractPlugin implements
    TaskPluginInterface
{

    const GLOSSARY_TRANSLATION = 'glossary_translation';
    const TRANSLATION_WORKER = 'translation.worker';

    /**
     * @return string
     */
    public function getName()
    {
        return self::TRANSLATION_WORKER;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {

        return sprintf(
            '%s.%s',
            $this->getStoreId(),
            self::GLOSSARY_TRANSLATION
        );
    }

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function run(QueueMessageInterface $queueMessage)
    {
        $this->getFacade()->processTranslationMessage($queueMessage);
    }

    /**
     * @return string
     */
    protected function getStoreId()
    {
        return $this->getDependencyContainer()
            ->getCurrentStore()
            ->getCurrentCountry()
        ;
    }

}
