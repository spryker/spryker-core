<?php

namespace Unit\SprykerFeature\Sdk\ZedRequest\Client;

use SprykerFeature\Shared\ZedRequest\Client\Message;
use SprykerFeature\Sdk\ZedRequest\Client\Response;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group Communication
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    protected function createFullResponse(TransferInterface $transfer)
    {
        $response = new Response(Locator::getInstance());

        $response->setSuccess(false);
        $response->addErrorMessages([new Message(['message'=>'error'])]);
        $response->addMessages([new Message(['message'=>'test'])]);
        $response->setTransfer($transfer);

        return $response;
    }

    public function testDefaultSuccessIsTrue()
    {
        $response = new Response(Locator::getInstance());
        $this->assertEquals(true, $response->isSuccess());
    }

    public function testDefaultTransferIsNull()
    {
        $response = new Response(Locator::getInstance());
        $this->assertEquals(null, $response->getTransfer());
    }

    public function testGetterAndSetters()
    {
        $locator = Locator::getInstance();

        $this->markTestSkipped();
        $transfer = new \Generated\Shared\Transfer\SystemTestMainTransfer();
        $transfer->setBar('string');

        $response = $this->createFullResponse($transfer);

        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals([new Message(['message'=>'error'])], $response->getErrorMessages());
        $this->assertEquals([new Message(['message'=>'test'])], $response->getMessages());
        $this->assertEquals($transfer, $response->getTransfer());
        $this->assertNotSame($transfer, $response->getTransfer());
        $this->assertNotSame($response->getTransfer(), $response->getTransfer());
    }

    public function testToArrayAndFromArray()
    {
        $this->markTestSkipped();
        $locator = Locator::getInstance();

        $transfer = new \Generated\Shared\Transfer\SystemTestMainTransfer();
        $transfer->setBar('string');

        $response = $this->createFullResponse($transfer);

        $array = $response->toArray();
        $this->assertTrue(is_array($array), 'toArray does not return array');

        $newResponse = new Response(Locator::getInstance(), $array);

        $this->assertEquals($response, $newResponse);
        $this->assertNotSame($response, $newResponse);
    }

    public function testHasMethods()
    {
        $response = new Response(Locator::getInstance());

        $response->addErrorMessage(new Message(['message'=>'error']));
        $response->addMessage(new Message(['message'=>'test']));

        $this->assertEquals(true, $response->hasErrorMessage('error'));
        $this->assertEquals(false, $response->hasErrorMessage('test'));
        $this->assertEquals(false, $response->hasMessage('error'));
        $this->assertEquals(true, $response->hasMessage('test'));
    }
}
