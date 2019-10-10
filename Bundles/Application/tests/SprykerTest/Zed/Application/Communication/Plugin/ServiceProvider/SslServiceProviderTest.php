<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Application\ApplicationConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group SslServiceProviderTest
 * Add your own group annotations below this line
 */
class SslServiceProviderTest extends Unit
{
    public const EXPECTED_CONTENT = 'not redirected';

    /**
     * @var \SprykerTest\Zed\Application\ApplicationCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testNoRedirectWhenSslNotEnabled()
    {
        $application = $this->tester->getApplicationForSslTest(static::EXPECTED_CONTENT, false);

        $request = $this->tester->getRequestForSslTest();

        $response = $application->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(static::EXPECTED_CONTENT, $response->getContent());
    }

    /**
     * @return void
     */
    public function testNoRedirectWhenHttpsIsOn()
    {
        $application = $this->tester->getApplicationForSslTest(static::EXPECTED_CONTENT);

        $request = $this->tester->getRequestForSslTest();
        $request->server->set('HTTPS', true);

        $response = $application->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(static::EXPECTED_CONTENT, $response->getContent());
    }

    /**
     * @return void
     */
    public function testNoRedirectWhenRequestIsFromTrustedProxyAndForwardedProtoIsHttps()
    {
        $this->tester->setConfig(ApplicationConstants::ZED_TRUSTED_PROXIES, ['127.0.0.1']);

        $application = $this->tester->getApplicationForSslTest(static::EXPECTED_CONTENT);

        $request = $this->tester->getRequestForSslTest();
        $request->server->set('REMOTE_ADDR', '127.0.0.1');
        $request->headers->set('X-FORWARDED-PROTO', 'https');

        $response = $application->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(static::EXPECTED_CONTENT, $response->getContent());
    }

    /**
     * @return void
     */
    public function testRedirectToHttpsWithoutTrustedProxySettings()
    {
        $application = $this->tester->getApplicationForSslTest();

        $request = $this->tester->getRequestForSslTest();
        $response = $application->handle($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @return void
     */
    public function testRedirectToHttpsWithTrustedProxySettingsAndForwardedProtoIsHttp()
    {
        $this->tester->setConfig(ApplicationConstants::ZED_TRUSTED_PROXIES, ['127.0.0.1']);

        $application = $this->tester->getApplicationForSslTest(static::EXPECTED_CONTENT);

        $request = $this->tester->getRequestForSslTest();
        $request->headers->set('X-FORWARDED-PROTO', 'http');

        $response = $application->handle($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }
}
