<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText\Model\Token;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\Model\Token\Token;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Model
 * @group Token
 * @group TokenTest
 * Add your own group annotations below this line
 */
class TokenTest extends Unit
{
    /**
     * @return void
     */
    public function testGenerateTokenMustReturnString()
    {
        $tokenService = new Token();
        $token = $tokenService->generate('token');

        $this->assertIsString($token);
    }

    /**
     * @return void
     */
    public function testCheckTokenMustReturnTrueIfTokenIsValid()
    {
        $tokenService = new Token();
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
        $tokenService = new Token();
        $rawToken = 'token';
        $token = $tokenService->generate($rawToken);

        $isValid = $tokenService->check('wrong' . $rawToken, $token);
        $this->assertFalse($isValid);
    }
}
