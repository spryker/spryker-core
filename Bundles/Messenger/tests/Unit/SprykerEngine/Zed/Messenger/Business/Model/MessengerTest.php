<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Messenger\Business\Model;

use SprykerEngine\Shared\Messenger\Business\Model\Message\Message;
use SprykerEngine\Zed\Messenger\Business\Model\Messenger;

class MessengerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Messenger
     */
    protected $messenger;

    protected $testMessages = [
        [
            'type' => 'success',
            'text' => 'Success 1',
        ],
        [
            'type' => 'success',
            'text' => 'Success 2',
        ],
        [
            'type' => 'notice',
            'text' => 'Notification 1',
        ],
        [
            'type' => 'error',
            'text' => 'Error 1',
        ],
        [
            'type' => 'success',
            'text' => 'Success 3',
        ],
    ];

    protected function setUp()
    {
        $this->messenger = new Messenger();
    }

    protected function addTestMessages()
    {
        foreach ($this->testMessages as $message) {
            $this->messenger->add($message['type'], $message['text']);
        }
    }

    public function testNewCreatedMessengerHasEmptyQueue()
    {
        $messages = $this->messenger->getAll();

        $this->assertEmpty($messages);
    }

    public function testGetByTypeReturnsOnlyMessagesWithRequestedTypeAndLeavesOtherMessagesInTheQueue()
    {
        // add some messages
        $this->addTestMessages();

        // get all messages of type success
        $messages = $this->messenger->getByType(Message::MESSAGE_SUCCESS);

        $this->assertCount(3, $messages);

        foreach ($messages as $message) {
            $this->assertEquals(
                Message::MESSAGE_SUCCESS,
                $message->getType()
            );
        }

        // get all messages left
        $messages = $this->messenger->getAll();

        $this->assertCount(2, $messages);

        foreach ($messages as $message) {
            $this->assertNotEquals(
                Message::MESSAGE_SUCCESS,
                $message->getType()
            );
        }
    }

}
