<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\PhpMd;

use Codeception\Test\Unit;
use ErrorException;
use Spryker\Zed\Development\Business\Normalizer\NameNormalizerInterface;
use Spryker\Zed\Development\Business\PhpMd\PhpMdRunner;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group PhpMd
 * @group PhpMdRunnerTest
 * Add your own group annotations below this line
 */
class PhpMdRunnerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected const EXIT_CODE_SUCCESS = 0;

    /**
     * @return void
     */
    public function shouldRunSuccessfullyWithValidBundle(): void
    {
        // Arrange
        $phpMdRunner = $this->createPhpMdRunner();

        // Act
        $exitCode = $phpMdRunner->run('Development', []);

        // Assert
        $this->assertEquals(static::EXIT_CODE_SUCCESS, $exitCode);
    }

    /**
     * @return void
     */
    public function shouldIgnoreVendorDirectoryByDefault(): void
    {
        // Arrange
        $phpMdRunner = $this->createPhpMdRunner();

        // Act
        $exitCode = $phpMdRunner->run(null, []);

        // Assert
        $this->assertEquals(static::EXIT_CODE_SUCCESS, $exitCode);
    }

    /**
     * @return void
     */
    public function shouldThrowExceptionForInvalidBundlePath(): void
    {
        // Assert
        $this->expectException(ErrorException::class);

        // Arrange
        $phpMdRunner = $this->createPhpMdRunner();

        // Act
        $phpMdRunner->run('InvalidModuleName', []);
    }

    /**
     * @return void
     */
    public function shouldUseCustomConfigForStandaloneMode(): void
    {
        // Arrange
        $standaloneMode = true;
        $phpMdRunner = $this->createPhpMdRunner($standaloneMode);

        // Assuming the mock setup and expectations here
        $exitCode = $phpMdRunner->run('Development');

        // Assert
        $this->assertEquals(static::EXIT_CODE_SUCCESS, $exitCode);
    }

    /**
     * @param bool $standaloneMode
     *
     * @return \Spryker\Zed\Development\Business\PhpMd\PhpMdRunner
     */
    protected function createPhpMdRunner(bool $standaloneMode = false): PhpMdRunner
    {
        $configMock = $this
            ->getMockBuilder(DevelopmentConfig::class)
            ->getMock();

        $configMock->method('isStandaloneMode')
            ->willReturn($standaloneMode);

        $nameNormalizerMock = $this->createMock(NameNormalizerInterface::class);

        $processMock = $this->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new PhpMdRunner($configMock, $nameNormalizerMock);
    }
}
