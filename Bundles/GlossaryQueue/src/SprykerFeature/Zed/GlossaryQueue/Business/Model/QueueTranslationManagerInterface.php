<?php

namespace SprykerFeature\Zed\GlossaryQueue\Business\Model;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface QueueTranslationManagerInterface
{

    /**
     * @param QueueMessageTransfer $queueMessage
     */
    public function processTranslationMessage(QueueMessageTransfer $queueMessage);

}
