<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ClientUnit\SprykerFeature\Client\Service;

use SprykerFeature\Client\Auth\Service\AuthClient;
use SprykerFeature\Client\Auth\Service\Token\TokenService;

/**
 * @group SprykerFeature
 * @group Client
 * @group Service
 * @group AuthClient
 */
class TokenServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerateTokenMustReturnString()
    {
        $tokenService = new TokenService();
        $token = $tokenService->generate('token');

        $this->assertInternalType('string', $token);
    }

    public function testCheckTokenMustReturnTrueIfTokenIsValid()
    {
        $tokenService = new TokenService();
        $rawToken = 'token';
        $token = $tokenService->generate($rawToken);

        $isValid = $tokenService->check($rawToken, $token);
        $this->assertTrue($isValid);
    }

    public function testCheckTokenMustReturnFalseIfTokenIsInValid()
    {
        $tokenService = new TokenService();
        $rawToken = 'token';
        $token = $tokenService->generate($rawToken);

        $isValid = $tokenService->check('wrong' . $rawToken, $token);
        $this->assertFalse($isValid);
    }

}
