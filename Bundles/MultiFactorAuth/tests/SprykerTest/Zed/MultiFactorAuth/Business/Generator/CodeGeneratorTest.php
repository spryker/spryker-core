<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth\Business\Facade\Generator;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGenerator;
use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface;
use SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Business
 * @group Facade
 * @group Generator
 * @group CodeGeneratorTest
 * Add your own group annotations below this line
 */
class CodeGeneratorTest extends Unit
{
    /**
     * @var int
     */
    protected const CODE_LENGTH = 6;

    /**
     * @var int
     */
    private const MIN_CODE_VALUE = 100000;

    /**
     * @var int
     */
    private const MAX_CODE_VALUE = 999999;

    /**
     * @var \SprykerTest\Zed\MultiFactorAuth\MultiFactorAuthBusinessTester
     */
    protected MultiFactorAuthBusinessTester $tester;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */

    protected MockObject|CodeGeneratorConfigProviderInterface $configProviderMock;

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGenerator
     */
    protected CodeGenerator $codeGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createCodeGeneratorConfigProviderMock();
        $this->codeGenerator = new CodeGenerator($this->configProviderMock);
    }

    /**
     * @return void
     */
    public function testGenerateCodeReturnsCorrectLength(): void
    {
        $code = ($this->codeGenerator->generateCode($this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL)))->getMultiFactorAuthCode()->getCode();

        $this->assertEquals(static::CODE_LENGTH, strlen($code));
    }

    /**
     * @return void
     */
    public function testGenerateCodeReturnsCodeWithinRange(): void
    {
        $code = ($this->codeGenerator->generateCode($this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL)))->getMultiFactorAuthCode()->getCode();

        $this->assertGreaterThanOrEqual(static::MIN_CODE_VALUE, (int)$code);
        $this->assertLessThanOrEqual(static::MAX_CODE_VALUE, (int)$code);
    }

    /**
     * @return void
     */
    public function testGenerateCodeReturnsDifferentCodes(): void
    {
        $code1 = ($this->codeGenerator->generateCode($this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL)))->getMultiFactorAuthCode()->getCode();
        $code2 = ($this->codeGenerator->generateCode($this->tester->createMultiFactorAuthTransfer($this->tester::TYPE_EMAIL)))->getMultiFactorAuthCode()->getCode();

        $this->assertNotEquals($code1, $code2);
    }

    /**
     * @return void
     */
    protected function createCodeGeneratorConfigProviderMock(): void
    {
        $this->configProviderMock = $this->createMock(CodeGeneratorConfigProviderInterface::class);
        $this->configProviderMock->method('getCodeLength')->willReturn(static::CODE_LENGTH);
        $this->configProviderMock->method('getCodeValidityTtl')->willReturn(10);
    }
}
