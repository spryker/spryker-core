<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\ZedRequest\Client;

use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\Client\ResponseInterface;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Unit\Spryker\Shared\ZedRequest\Client\Fixture\AbstractHttpClient;
use Unit\Spryker\Shared\ZedRequest\Client\Fixture\Transfer;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group ZedRequest
 * @group Client
 * @group AbstractHttpClientTest
 */
class AbstractHttpClientTest extends PHPUnit_Framework_TestCase
{

    const TRANSFER_VALUE = 'catface';

    /**
     * @return void
     */
    public function testRequest()
    {
        $abstractRequest = $this->getAbstractRequestMock();

        $body = json_encode([
            ResponseInterface::TRANSFER => ['key' => static::TRANSFER_VALUE],
            ResponseInterface::TRANSFER_CLASSNAME => Transfer::class,
        ]);
        $abstractRequest->expects($this->once())->method('sendRequest')->willReturn(new Response(200, [], $body));

        $response = $abstractRequest->request('?foo=bar');
        $transfer = $response->getTransfer();
        $this->assertSame(static::TRANSFER_VALUE, $transfer->getKey());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Unit\Spryker\Shared\ZedRequest\Client\Fixture\AbstractHttpClient
     */
    protected function getAbstractRequestMock()
    {
        $baseUrl = 'http://' . Config::get(ZedRequestConstants::HOST_ZED_API);
        $url = $baseUrl . '/';

        $utilNetworkService = new UtilNetworkService();

        return $this->getMockBuilder(AbstractHttpClient::class)->setMethods(['sendRequest'])->setConstructorArgs([$url, $utilNetworkService])->getMock();
    }

}
