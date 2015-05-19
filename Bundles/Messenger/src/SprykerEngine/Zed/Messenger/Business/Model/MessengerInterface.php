<?php

namespace SprykerEngine\Zed\Messenger\Business\Model;

use SprykerEngine\Zed\Messenger\Business\Model\Exception\MessageTypeNotFoundException;
use SprykerEngine\Zed\Messenger\Business\Model\Message\MessageInterface;
use SprykerEngine\Zed\Messenger\Business\Model\Presenter\ObservingPresenterInterface;

/**
 * Interface MessengerInterface
 *
 * @method Messenger addSuccess($key, $options = [])
 * @method Messenger addError($key, $options = [])
 * @method Messenger addNotice($key, $options = [])
 * @method Messenger addWarning($key, $options = [])
 */
interface MessengerInterface
{
    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return Messenger
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
     * @param ObservingPresenterInterface $presenter
     *
     * @return MessengerInterface
     */
    public function registerPresenter(ObservingPresenterInterface $presenter);
}