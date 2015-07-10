<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Messenger\Business\MessengerFacade;
use SprykerEngine\Shared\Messenger\Business\Model\Message\Message;

class MessengerFacadeTest extends Test
{

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

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var MessengerFacade
     */
    protected $messengerFacade;

    protected function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->messengerFacade = $this->locator->messenger()->facade();
    }

    protected function addTestMessages()
    {
        foreach ($this->testMessages as $message) {
            $this->messengerFacade->add($message['type'], $message['text']);
        }
    }

    public function testNewCreatedMessengerHasEmptyQueue()
    {
        $messages = $this->messengerFacade->getAll();

        $this->assertEmpty($messages);
    }

    public function testGetByTypeReturnsOnlyMessagesWithRequestedTypeAndLeavesOtherMessagesInTheQueue()
    {
        // add some messages
        $this->addTestMessages();

        // get all messages of type success
        $messages = $this->messengerFacade->getByType(Message::MESSAGE_SUCCESS);

        $this->assertCount(3, $messages);

        foreach ($messages as $message) {
            $this->assertEquals(
                Message::MESSAGE_SUCCESS,
                $message->getType()
            );
        }

        // get all messages left
        $messages = $this->messengerFacade->getAll();

        $this->assertCount(2, $messages);

        foreach ($messages as $message) {
            $this->assertNotEquals(
                Message::MESSAGE_SUCCESS,
                $message->getType()
            );
        }
    }

}
