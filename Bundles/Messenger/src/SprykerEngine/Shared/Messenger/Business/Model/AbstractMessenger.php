<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Messenger\Business\Model;

use SprykerEngine\Shared\Messenger\Business\Model\Message\Message;
use SprykerEngine\Shared\Messenger\Business\Model\Message\MessageInterface;

abstract class AbstractMessenger implements MessengerInterface
{

    /**
     * @var MessageInterface[]
     */
    protected $messages = [];

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return MessengerInterface
     */
    public function add($type, $message, array $options = [])
    {
        $this->messages[] = new Message(
            $type,
            $message,
            $options
        );

        return $this;
    }

    /**
     * @return MessageInterface[]
     */
    public function getAll()
    {
        $messages = $this->messages;

        $this->messages = [];

        return $messages;
    }

    /**
     * @param string $type
     *
     * @return MessageInterface[]
     */
    public function getByType($type = null)
    {
        $messages = array_filter(
            $this->messages,
            function (MessageInterface $message) use ($type) {
                return $message->getType() === $type;
            }
        );

        $this->messages = array_filter(
            $this->messages,
            function (MessageInterface $message) use ($type) {
                return $message->getType() !== $type;
            }
        );

        return $messages;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function success($message, array $context = [])
    {
        return $this->add(
            Message::MESSAGE_SUCCESS,
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
     * @return MessengerInterface
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
     * @return MessengerInterface
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
     * @return MessengerInterface
     */
    public function log($level, $message, array $context = [])
    {
        return $this->add($level, $message, $context);
    }

}
