<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication\Plugin;

use Generated\Shared\Queue\QueueMessageInterface;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

class GlossaryTaskWorkerPlugin extends AbstractPlugin implements
    TaskPluginInterface
{
    const GLOSSARY_TRANSLATION = 'glossary_translation';

    /**
     * @return string
     */
    public function getName()
    {
        return 'glossary-worker';
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
        var_dump($queueMessage);
    }

    /**
     * @return string
     */
    protected function getStoreId()
    {
        return Store::getInstance()->getCurrentCountry();
    }
}
