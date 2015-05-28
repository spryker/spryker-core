<?php

namespace Unit\SprykerEngine\Zed\Messenger\Business\Model;

use SprykerEngine\Shared\Messenger\Business\Model\Message\Message;
use SprykerEngine\Zed\Messenger\Business\Model\Messenger;

class MessengerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Messenger
     */
    protected $messenger;

    protected function setUp()
    {
        $this->messenger = new Messenger();
    }

    public function testAddSuccess()
    {
        $this->messenger->addSuccess('Success');
    }

    public function testGetByType()
    {
        // add some messages
        $this->messenger
            ->addSuccess('Success 1')
            ->addSuccess('Success 2')
            ->addNotice('Notification 1')
            ->addError('Error 1')
            ->addSuccess('Success 3');

        $messages = $this->messenger->getAll(Message::MESSAGE_SUCCESS);

        foreach ($messages as $message) {
            $this->assertEquals(
                Message::MESSAGE_SUCCESS,
                $message->getType()
            );
        }
    }
}
