<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business;

use Generated\Shared\Transfer\QueueMessageTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method GlossaryQueueDependencyContainer getDependencyContainer()
 */
class GlossaryQueueFacade extends AbstractFacade
{

    /**
     * @param QueueMessageTransfer $queueMessage
     */
    public function processTranslationMessage(QueueMessageTransfer $queueMessage)
    {
        $this->getDependencyContainer()
            ->createQueueTranslationManager()
            ->processTranslationMessage($queueMessage);
    }

}
