<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger;

use SprykerFeature\Zed\Application\Business\Model\Messenger\Exception\MessageTypeNotFoundException;
use SprykerFeature\Zed\Application\Business\Model\Messenger\Message\Message;
use SprykerFeature\Zed\Application\Business\Model\Messenger\Message\MessageInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface as LegacyMessengerInterface;
use SprykerFeature\Zed\Application\Business\Model\Messenger\MessengerInterface;
use SprykerFeature\Zed\Application\Business\Model\Messenger\Presenter\ObservingPresenterInterface;

/**
 * Class Messenger
 * @package SprykerFeature\Zed\Application\Business\Model\Messenger
 *
 * @method Messenger addSuccess($key, $options = [])
 * @method Messenger addError($key, $options = [])
 * @method Messenger addNotice($key, $options = [])
 * @method Messenger addWarning($key, $options = [])
 */
class Messenger implements MessengerInterface, LegacyMessengerInterface
{
    protected $validMessageTypes = [
        Message::MESSAGE_ALERT,
        Message::MESSAGE_CRITICAL,
        Message::MESSAGE_DEBUG,
        Message::MESSAGE_EMERGENCY,
        Message::MESSAGE_ERROR,
        Message::MESSAGE_INFO,
        Message::MESSAGE_NOTICE,
        Message::MESSAGE_SUCCESS,
        Message::MESSAGE_WARNING,
    ];

    /**
     * @var MessageInterface[]
     */
    protected $messages = [];

    /**
     * @var ObservingPresenterInterface[]
     */
    protected $observingPresenters = [];

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return Messenger
     * @throws MessageTypeNotFoundException
     */
    public function add($type, $message, array $options = [])
    {
        if (!in_array($type, $this->validMessageTypes)) {
            throw new MessageTypeNotFoundException();
        }

        $this->messages[] = new Message(
            $type,
            $message,
            $options
        );

        $this->notify();

        return $this;
    }

    /**
     * @param string $type
     *
     * @return MessageInterface
     */
    public function get($type = null)
    {
        if ($type === null) {
            return array_shift($this->messages);
        }

        foreach ($this->messages as $key => $message) {
            if ($message->getType() === $type) {
                $returnMessage = array_splice($this->messages, $key, 1);

                return $returnMessage[0];
            }
        }

        return null;
    }

    /**
     * @param string $type
     *
     * @return MessageInterface[]
     */
    public function getAll($type = null)
    {
        if ($type === null) {
            $messages = $this->messages;

            $this->messages = [];

            return $messages;
        }

        $messages = [];

        while (null !== $message = $this->get($type)) {
            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return Messenger
     */
    function __call($name, $arguments)
    {
        if (0 === strpos($name, 'add')) {
            $type    = lcfirst(substr($name, 3));
            $message = $arguments[0];
            $options = isset($arguments[1]) ? $arguments[1] : [];

            return $this->add($type, $message, $options);
        }
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

    /**
     * @param ObservingPresenterInterface $presenter
     *
     * @return MessengerInterface
     */
    public function registerPresenter(ObservingPresenterInterface $presenter)
    {
        $this->observingPresenters[] = $presenter;

        return $this;
    }

    /**
     * notifies registered presenters about available updates
     */
    protected function notify()
    {
        foreach ($this->observingPresenters as $presenter) {
            $presenter->update();
        }
    }
}