<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\ArchitectureSniffer;

use Codeception\Test\Unit;
use Exception;
use Laminas\Config\Reader\ReaderInterface;
use Spryker\Zed\Development\Business\ArchitectureSniffer\ArchitectureSniffer;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group ArchitectureSniffer
 * @group ArchitectureSnifferTest
 * Add your own group annotations below this line
 */
class ArchitectureSnifferTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function shouldReturnEmptyArrayForEmptyConfiguration(): void
    {
        // Arrange
        $xmlReaderMock = $this->createMock(ReaderInterface::class);
        $configurationBuilderMock = $this->createMock(SnifferConfigurationBuilderInterface::class);
        $configurationBuilderMock->method('getConfiguration')->willReturn([]);

        $architectureSniffer = new ArchitectureSniffer($xmlReaderMock, 'command', $configurationBuilderMock);

        // Act
        $result = $architectureSniffer->run('directory', []);

        // Assert
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function shouldThrowExceptionForInvalidDirectory(): void
    {
        // Assert
        $this->expectException(Exception::class);

        // Arrange
        $xmlReaderMock = $this->createMock(ReaderInterface::class);
        $configurationBuilderMock = $this->createMock(SnifferConfigurationBuilderInterface::class);
        $configurationBuilderMock
            ->method('getConfiguration')
            ->willReturn(['someOption' => 'value']);

        $architectureSniffer = new ArchitectureSniffer($xmlReaderMock, 'command', $configurationBuilderMock);

        // Act
        $architectureSniffer->run('invalid/directory', []);
    }

    /**
     * @return void
     */
    public function shouldProcessValidDirectory(): void
    {
        // Arrange
        $xmlReaderMock = $this->createMock(ReaderInterface::class);
        $xmlReaderMock->method('fromString')->willReturn([
            'file' => [
                '@attributes' => ['name' => 'someFile'],
                'violation' => [
                    ['@attributes' => ['priority' => 1], '_' => 'Some violation'],
                ],
            ],
        ]);
        $configurationBuilderMock = $this->createMock(SnifferConfigurationBuilderInterface::class);
        $configurationBuilderMock
            ->method('getConfiguration')
            ->willReturn(['priority' => 1]);

        $architectureSniffer = new ArchitectureSniffer($xmlReaderMock, 'command', $configurationBuilderMock);

        // Act
        $result = $architectureSniffer->run('vendor/spryker/spryker/Bundles/Development', []);

        // Assert
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('fileName', $result[0]);
        $this->assertArrayHasKey('description', $result[0]);
    }

    /**
     * @return void
     */
    public function shouldHandleDryRunOption(): void
    {
        // Arrange
        $xmlReaderMock = $this->createMock(ReaderInterface::class);
        $configurationBuilderMock = $this->createMock(SnifferConfigurationBuilderInterface::class);
        $configurationBuilderMock
            ->method('getConfiguration')
            ->willReturn(['dry-run' => true]);

        $architectureSniffer = new ArchitectureSniffer($xmlReaderMock, 'command', $configurationBuilderMock);

        // Assert
        $this->expectOutputRegex('/command/');

        // Act
        $architectureSniffer->run('directory', ['dry-run' => true]);
    }

    /**
     * @return void
     */
    public function shouldIgnoreErrorsBasedOnConfiguration(): void
    {
        // Arrabge
        $xmlReaderMock = $this->createMock(ReaderInterface::class);
        $xmlReaderMock->method('fromString')->willReturn([
            'file' => [
                '@attributes' => ['name' => 'someFile'],
                'violation' => [
                    ['@attributes' => ['priority' => 1], '_' => 'Ignored violation'],
                    ['@attributes' => ['priority' => 2], '_' => 'Processed violation'],
                ],
            ],
        ]);
        $configurationBuilderMock = $this->createMock(SnifferConfigurationBuilderInterface::class);
        $configurationBuilderMock
            ->method('getConfiguration')
            ->willReturn(['ignoreErrors' => ['/Ignored violation/']]);

        $architectureSniffer = new ArchitectureSniffer($xmlReaderMock, 'command', $configurationBuilderMock);

        // Act
        $result = $architectureSniffer->run('directory', ['ignoreErrors' => ['/Ignored violation/']]);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('Processed violation', $result[0]['description']);
    }
}
