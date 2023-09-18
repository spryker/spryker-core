<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\TaxApp;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use Spryker\Client\TaxApp\Dependency\Client\TaxAppToStoreClientInterface;
use Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Client\TaxApp\PHPMD)
 */
class TaxAppClientTester extends Actor
{
    use _generated\TaxAppClientTesterActions;

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return void
     */
    public function mockHttpClient(Response $response): void
    {
        $httpClientMock = Stub::makeEmpty(TaxAppToHttpClientAdapterInterface::class, [
            'request' => $response,
        ]);

        $this->mockFactoryMethod('getHttpClient', $httpClientMock);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function mockStoreClient(StoreTransfer $storeTransfer): void
    {
        $storeClientMock = Stub::makeEmpty(TaxAppToStoreClientInterface::class, [
            'getStoreByName' => $storeTransfer,
        ]);

        $this->mockFactoryMethod('getStoreClient', $storeClientMock);
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    public function haveValidResponse(): Response
    {
        $handler = fopen('data://text/plain,' . json_encode(['responseData']), 'r');
        $stream = new Stream($handler);

        return new Response(200, [], $stream);
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    public function haveEmptyResponse(): Response
    {
        return new Response(200);
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    public function haveErrorResponse(): Response
    {
        return new Response(422);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function assertTaxCalculationResponseIsNotEmpty(TaxCalculationResponseTransfer $responseTransfer): void
    {
        $this->assertNotNull($responseTransfer);
        $this->assertInstanceOf(TaxCalculationResponseTransfer::class, $responseTransfer);
    }
}
