<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Communication\Plugin;

use Psr\Log\LogLevel;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacade getFacade()
 * @method \Spryker\Zed\Installer\Communication\InstallerCommunicationFactory getFactory()
 */
abstract class AbstractInstallerPlugin extends AbstractPlugin implements MessengerInterface
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
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     *
     * @return self
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->messenger) {
            $this->messenger->log($level, $message, $context);
        }

        return $this;
    }

}
