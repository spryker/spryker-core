<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Symfony\Form\RequestTokenProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Symfony
 * @group Form
 * @group RequestTokenProvider
 * @group TokenHashGeneratorTest
 */
class TokenHashGeneratorTest extends Unit
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $sessionMock;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator
     */
    protected $tokenGenerator;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tokenGenerator = new TokenHashGenerator();
    }

    /**
     * @return void
     */
    public function testTokenHashGeneratorGeneratesRandomHashes()
    {
        $hashOne = $this->tokenGenerator->generateToken();
        $hashTwo = $this->tokenGenerator->generateToken();

        $this->assertNotEmpty($hashOne);
        $this->assertNotEmpty($hashTwo);

        $this->assertTrue($this->tokenGenerator->checkTokenEquals($hashOne, $hashOne));
        $this->assertFalse($this->tokenGenerator->checkTokenEquals($hashOne, $hashTwo));
    }

}
