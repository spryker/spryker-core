<?php

namespace ProjectA\Queue;

interface PersistableInterface
{

    /**
     * @param QueueMessage $queueMessage
     * @return mixed
     */
    public function handleStoring(QueueMessage $queueMessage);

} 
