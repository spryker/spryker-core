<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business\Model;

use Generated\Shared\Queue\QueueMessageInterface;

interface QueueTranslationManagerInterface
{

    /**
     * @param QueueMessageInterface $queueMessage
     */
    public function processTranslationMessage(QueueMessageInterface $queueMessage);

}
