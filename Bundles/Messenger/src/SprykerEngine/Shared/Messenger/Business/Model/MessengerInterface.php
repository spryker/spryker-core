<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Messenger\Business\Model;

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
     */
    public function add($type, $message, array $options = []);

    /**
     * @return MessageInterface[]
     */
    public function getAll();

    /**
     * @param string $type
     *
     * @return MessageInterface[]
     */
    public function getByType($type = null);

    /**
     * @param string $key
     * @param array $options
     *
     * @return MessengerInterface
     */
    public function success($key, array $options = []);

}
