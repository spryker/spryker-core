<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\ZedRequest\Client;

use GuzzleHttp\Psr7\Response;
use Spryker\Client\Auth\AuthClient;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\Client\ResponseInterface;
use Unit\Spryker\Shared\Transfer\Fixtures\AbstractTransfer;
use Unit\Spryker\Shared\ZedRequest\Client\Fixture\AbstractHttpClient;

/**
 * @group Spryker
 * @group Shared
 * @group ZedRequest
 * @group Client
 * @group AbstractHttpClient
 */
class AbstractHttpClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRequest()
    {
        $abstractRequest = $this->getAbstractRequestMock();

        $body = json_encode([
            ResponseInterface::TRANSFER => ['bool' => true],
            ResponseInterface::TRANSFER_CLASSNAME => AbstractTransfer::class,
        ]);
        $abstractRequest->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], $body));

        $response = $abstractRequest->request('?foo=bar');

        /**
         * @var AbstractTransfer $transfer
         */
        $transfer = $response->getTransfer();
        $this->assertTrue($transfer->getBool());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractHttpClient
     */
    protected function getAbstractRequestMock()
    {
        $baseUrl = 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI);

        $client = new AuthClient();
        $url = $baseUrl . '/';

        return $this->getMockBuilder(AbstractHttpClient::class)->setMethods(['sendRequest'])->setConstructorArgs([$client, $url])->getMock();
    }

}
