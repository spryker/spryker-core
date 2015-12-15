<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ClientUnit\Spryker\Client\Auth\Token;

use Spryker\Client\Auth\Token\TokenService;

/**
 * @group Spryker
 * @group Client
 * @group Service
 * @group AuthClient
 */
class TokenServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGenerateTokenMustReturnString()
    {
        $tokenService = new TokenService();
        $token = $tokenService->generate('token');

        $this->assertInternalType('string', $token);
    }

    /**
     * @return void
     */
    public function testCheckTokenMustReturnTrueIfTokenIsValid()
    {
        $tokenService = new TokenService();
        $rawToken = 'token';
        $token = $tokenService->generate($rawToken);

        $isValid = $tokenService->check($rawToken, $token);
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckTokenMustReturnFalseIfTokenIsInValid()
    {
        $tokenService = new TokenService();
        $rawToken = 'token';
        $token = $tokenService->generate($rawToken);

        $isValid = $tokenService->check('wrong' . $rawToken, $token);
        $this->assertFalse($isValid);
    }

}
