<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Client\ZedRequest\Service\Client;

use SprykerFeature\Shared\ZedRequest\Client\Message;
use SprykerFeature\Client\ZedRequest\Service\Client\Response;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerEngine\Zed\Kernel\Locator;
use Unit\SprykerFeature\Client\ZedRequest\Service\Client\Fixture\TestTransfer;

/**
 * @group SprykerFeature
 * @group Client
 * @group ZedRequest
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param TransferInterface $transfer
     *
     * @return Response
     */
    protected function createFullResponse(TransferInterface $transfer)
    {
        $response = new Response();

        $response->setSuccess(false);
        $response->addErrorMessages([new Message(['message' => 'error'])]);
        $response->addMessages([new Message(['message' => 'test'])]);
        $response->setTransfer($transfer);

        return $response;
    }

    public function testDefaultSuccessIsTrue()
    {
        $response = new Response();
        $this->assertEquals(true, $response->isSuccess());
    }

    public function testDefaultTransferIsNull()
    {
        $response = new Response();
        $this->assertEquals(null, $response->getTransfer());
    }

    public function testGetterAndSetters()
    {
        $locator = Locator::getInstance();

        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $response = $this->createFullResponse($transfer);

        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals([new Message(['message' => 'error'])], $response->getErrorMessages());
        $this->assertEquals([new Message(['message' => 'test'])], $response->getMessages());
        $this->assertEquals($transfer, $response->getTransfer());
        $this->assertNotSame($transfer, $response->getTransfer());
        $this->assertNotSame($response->getTransfer(), $response->getTransfer());
    }

    public function testToArrayAndFromArray()
    {
        $locator = Locator::getInstance();

        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $response = $this->createFullResponse($transfer);

        $array = $response->toArray();
        $this->assertTrue(is_array($array), 'toArray does not return array');

        $newResponse = new Response($array);

        $this->assertEquals($response, $newResponse);
        $this->assertNotSame($response, $newResponse);
    }

    public function testHasMethods()
    {
        $response = new Response();

        $response->addErrorMessage(new Message(['message' => 'error']));
        $response->addMessage(new Message(['message' => 'test']));

        $this->assertEquals(true, $response->hasErrorMessage('error'));
        $this->assertEquals(false, $response->hasErrorMessage('test'));
        $this->assertEquals(false, $response->hasMessage('error'));
        $this->assertEquals(true, $response->hasMessage('test'));
    }

}
