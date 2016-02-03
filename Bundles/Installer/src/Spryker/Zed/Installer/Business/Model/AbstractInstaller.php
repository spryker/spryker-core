<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Business\Model;

use Psr\Log\AbstractLogger;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

abstract class AbstractInstaller extends AbstractLogger implements MessengerInterface
{

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return self
     */
    public function setMessenger(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * @return void
     */
    abstract public function install();

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->messenger->log($level, $message, $context);
    }

}
