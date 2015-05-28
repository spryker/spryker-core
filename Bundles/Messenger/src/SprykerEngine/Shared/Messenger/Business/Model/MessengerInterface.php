<?php

namespace SprykerEngine\Shared\Messenger\Business\Model;

use SprykerEngine\Shared\Messenger\Business\Model\Exception\MessageTypeNotFoundException;
use SprykerEngine\Shared\Messenger\Business\Model\Message\MessageInterface;
use Psr\Log\LoggerInterface;

interface MessengerInterface extends LoggerInterface
{

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return MessengerInterface
     * @throws MessageTypeNotFoundException
     */
    public function add($type, $message, array $options = []);

    /**
     * @param string $type
     *
     * @return MessageInterface
     */
    public function get($type = null);

    /**
     * @param string $type
     *
     * @return MessageInterface[]
     */
    public function getAll($type = null);

    /**
     * @param string $key
     * @param array $options
     *
     * @return MessengerInterface
     */
    public function success($key, array $options = []);

}
