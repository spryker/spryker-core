<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method GlossaryQueueDependencyContainer getDependencyContainer()
 */
class GlossaryQueueFacade extends AbstractFacade
{

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function processTranslationMessage(QueueMessageInterface $queueMessage)
    {
        $this->getDependencyContainer()
            ->createQueueTranslationManager()
            ->processTranslationMessage($queueMessage)
        ;
    }

}
