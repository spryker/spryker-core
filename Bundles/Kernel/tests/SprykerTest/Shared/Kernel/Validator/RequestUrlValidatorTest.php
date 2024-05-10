<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Validator;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Exception\ForbiddenExternalRedirectException;
use Spryker\Shared\Kernel\Validator\RedirectUrlValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\Validation;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group Validator
 * @group RequestUrlValidatorTest
 * Add your own group annotations below this line
 */
class RequestUrlValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const CURRENT_DOMAIN = 'currentdomain.com';

    /**
     * @var int
     */
    protected const REQUEST_TYPE = 1;

    /**
     * @dataProvider redirectUrlValidationDataProvider
     *
     * @param array<string> $allowedDomains
     * @param string $url
     * @param bool $isStrictDomainRedirectEnabled
     * @param bool $redirectRequest
     * @param bool $validationResult
     *
     * @return void
     */
    public function testValidationFailsWhenDomainIsNotAllowed(
        array $allowedDomains,
        string $url,
        bool $isStrictDomainRedirectEnabled,
        bool $redirectRequest,
        bool $validationResult
    ): void {
        $redirectUrlValidator = new RedirectUrlValidator(
            Validation::createValidator(),
            $allowedDomains,
            $isStrictDomainRedirectEnabled,
        );
        $responseEvent = $this->createResponseEvent($url, $redirectRequest);

        if (!$validationResult) {
            $this->expectException(ForbiddenExternalRedirectException::class);
        }

        $redirectUrlValidator->validateRedirectUrl($responseEvent);
    }

    /**
     * @return array
     */
    public function redirectUrlValidationDataProvider(): array
    {
        return [
            'domain is allowed' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => 'http://alloweddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'domain is not allowed' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'strict domain is disabled and domain is not allowed' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'strict domain redirect enabled' => false,
                'redirect request' => true,
                'validation result' => true,
            ],
            'allowed domains are not set' => [
                'allowed domains' => [],
                'url' => 'http://forbiddendomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'is not redirect response' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => false,
                'validation result' => true,
            ],
            'redirect to current domain' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => 'http://currentdomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'allowed domain without protocol' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '//alloweddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'domain without protocol with backslash' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/\invaliddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'domain without protocol with backslash on first position' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '\/invaliddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'domain without protocol with two backslashes' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '\\\\invaliddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'domain without protocol with three slashes' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '///invaliddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'domain without protocol with four slashes' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '////invaliddomain.com',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => false,
            ],
            'relative url to root page' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'relative url to some page' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/some-page',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'relative url to some page with child page' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/some-page/some-page-child',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'relative url to some page with non-alphabet name' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/0123-._~%!$&\'()*+,;=:@',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
            'relative url to some page with underscore' => [
                'allowed domains' => ['alloweddomain.com'],
                'url' => '/?_store=DE',
                'strict domain redirect enabled' => true,
                'redirect request' => true,
                'validation result' => true,
            ],
        ];
    }

    /**
     * @param string $url
     * @param bool $isRedirect
     *
     * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
     */
    protected function createResponseEvent(string $url, bool $isRedirect = true): ResponseEvent
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('getHost')->willReturn(static::CURRENT_DOMAIN);
        $headerBagMock = $this->createMock(ResponseHeaderBag::class);
        $headerBagMock->method('get')->willReturn($url);
        $response = new Response();
        $response->headers = $headerBagMock;

        if ($isRedirect) {
            $response->setStatusCode(300);
        }

        return new ResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $requestMock,
            static::REQUEST_TYPE,
            $response,
        );
    }
}
