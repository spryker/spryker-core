<?php

namespace Unit\SprykerFeature\Zed\Application\Business\Model\Messenger;

use SprykerFeature\Zed\Application\Business\Model\Messenger\Message\Message;
use SprykerFeature\Zed\Application\Business\Model\Messenger\Messenger;

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

    /**
     * @expectedException \SprykerFeature\Zed\Application\Business\Model\Messenger\Exception\MessageTypeNotFoundException
     */
    public function testAddInvalid()
    {
        $this->messenger->add('invalid', 'invalid');
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

        $message = $this->messenger->get(Message::MESSAGE_NOTICE);

        $this->assertEquals(
            Message::MESSAGE_NOTICE,
            $message->getType()
        );
    }
}