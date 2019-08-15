<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Symfony\Form\RequestTokenProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Symfony
 * @group Form
 * @group RequestTokenProvider
 * @group TokenHashGeneratorTest
 * Add your own group annotations below this line
 */
class TokenHashGeneratorTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
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
