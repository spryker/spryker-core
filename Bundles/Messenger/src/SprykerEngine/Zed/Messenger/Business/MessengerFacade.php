<?php

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Shared\Messenger\Business\Model\Exception\MessageTypeNotFoundException;
use SprykerEngine\Shared\Messenger\Business\Model\Message\Message;
use SprykerEngine\Shared\Messenger\Business\Model\Message\MessageInterface;
use SprykerEngine\Zed\Messenger\Business\Model\Messenger;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Shared\Messenger\Communication\Presenter\ObservingPresenterInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface as LegacyMessengerInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @method MessengerInterface addSuccess($key, $options = [])
 * @method MessengerInterface addError($key, $options = [])
 * @method MessengerInterface addNotice($key, $options = [])
 * @method MessengerInterface addWarning($key, $options = [])
 */
class MessengerFacade extends AbstractFacade implements MessengerInterface,
    LegacyMessengerInterface
{
    protected $messenger;

    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);

        $this->messenger = $this->getDependencyContainer()->getMessenger();
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function getAll($type = null)
    {
        return $this->messenger->getAll($type);
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return MessengerInterface
     * @throws MessageTypeNotFoundException
     */
    public function add($type, $message, array $options = [])
    {
        return $this->messenger->add(
            $type,
            $message,
            $options
        );
    }

    /**
     * @param string $type
     *
     * @return MessageInterface
     */
    public function get($type = null)
    {
        return $this->messenger->get($type);
    }

    /**
     * @param ObservingPresenterInterface $presenter
     *
     * @return MessengerInterface
     */
    public function registerPresenter(ObservingPresenterInterface $presenter)
    {
        return $this->messenger->registerPresenter(
            $presenter
        );
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function alert($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_ALERT,
            $message,
            $context
        );
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function critical($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_CRITICAL,
            $message,
            $context
        );
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function emergency($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_EMERGENCY,
            $message,
            $context
        );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function error($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_ERROR,
            $message,
            $context
        );
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function warning($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_WARNING,
            $message,
            $context
        );
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function notice($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_NOTICE,
            $message,
            $context
        );
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function info($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_INFO,
            $message,
            $context
        );
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function debug($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_DEBUG,
            $message,
            $context
        );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return Messenger
     */
    public function log($level, $message, array $context = [])
    {
        return $this->add($level, $message, $context);
    }


    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'add')) {
            $type    = lcfirst(substr($name, 3));
            $message = $arguments[0];
            $options = isset($arguments[1]) ? $arguments[1] : [];

            return $this->add($type, $message, $options);
        }
    }

}
