<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Client;

use Codeception\Test\Unit;
use GuzzleHttp\Psr7\Response;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\Client\ResponseInterface;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use SprykerTest\Shared\ZedRequest\Client\Fixture\AbstractHttpClient;
use SprykerTest\Shared\ZedRequest\Client\Fixture\Transfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group ZedRequest
 * @group Client
 * @group AbstractHttpClientTest
 * Add your own group annotations below this line
 */
class AbstractHttpClientTest extends Unit
{
    public const TRANSFER_VALUE = 'catface';

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
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerTest\Shared\ZedRequest\Client\Fixture\AbstractHttpClient
     */
    protected function getAbstractRequestMock()
    {
        $baseUrl = Config::get(ZedRequestConstants::BASE_URL_ZED_API);
        $url = $baseUrl . '/';

        $utilNetworkService = new UtilNetworkService();

        return $this->getMockBuilder(AbstractHttpClient::class)->setMethods(['sendRequest'])->setConstructorArgs([$url, $utilNetworkService])->getMock();
    }
}
