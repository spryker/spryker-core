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
                'allowedDomains' => ['alloweddomain.com'],
                'url' => 'http://alloweddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'domain is not allowed' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'strict domain is disabled and domain is not allowed' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'isStrictDomainRedirectEnabled' => false,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'allowedDomains are not set' => [
                'allowedDomains' => [],
                'url' => 'http://forbiddendomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'is not redirect response' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => 'http://forbiddendomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => false,
                'validationResult' => true,
            ],
            'redirect to current domain' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => 'http://currentdomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'allowed domain without protocol' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '//alloweddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'domain without protocol with backslash' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/\invaliddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'domain without protocol with backslash on first position' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '\/invaliddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'domain without protocol with two backslashes' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '\\\\invaliddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'domain without protocol with three slashes' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '///invaliddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'domain without protocol with four slashes' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '////invaliddomain.com',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => false,
            ],
            'relative url to root page' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/some-page',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with child page' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/some-page/some-page-child',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with non-alphabet name' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/0123-._~%!$&\'()*+,;=:@',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with underscore' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/?_store=DE',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with fragment identifier' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/some-page/index/some-page-child?id=1#codes',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with special characters' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/some-page/index/some-page-child?$param=&param2=%7B%22id%22:%22test%22&param3=',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some page with umlauts' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/some-pageäöüÄÖÜß/indexäöüÄÖÜß/some-page-childäöüÄÖÜß',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
            ],
            'relative url to some with a few parameters' => [
                'allowedDomains' => ['alloweddomain.com'],
                'url' => '/en/index?q=Smart&format=svg',
                'isStrictDomainRedirectEnabled' => true,
                'redirectRequest' => true,
                'validationResult' => true,
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
