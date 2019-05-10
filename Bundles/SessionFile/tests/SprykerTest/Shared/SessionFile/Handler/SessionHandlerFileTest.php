<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SessionFile\Handler;

use Codeception\Test\Unit;
use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceBridge;
use Spryker\Shared\SessionFile\Handler\SessionHandlerFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group SessionFile
 * @group Handler
 * @group SessionHandlerFileTest
 * Add your own group annotations below this line
 */
class SessionHandlerFileTest extends Unit
{
    public const LIFETIME = 20;

    public const SESSION_NAME = 'sessionName';

    public const SESSION_ID = 'sessionId';
    public const SESSION_ID_2 = 'anotherSessionId';

    public const SESSION_DATA = 'sessionData';

    /**
     * @return void
     */
    public function tearDown(): void
    {
        if (is_dir($this->getFixtureDirectory())) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getFixtureDirectory());
        }
    }

    /**
     * @return string
     */
    private function getFixtureDirectory(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures';
    }

    /**
     * @return string
     */
    protected function getSavePath(): string
    {
        return $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . 'Sessions';
    }

    /**
     * @return void
     */
    public function testCallOpenMustCreateDirectoryIfNotExists(): void
    {
        $this->assertFalse(is_dir($this->getSavePath()));

        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);

        $this->assertTrue(is_dir($this->getSavePath()));
    }

    /**
     * @return void
     */
    public function testCallOpenMustReturnTrue(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $result = $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCallCloseMustReturnTrue(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $result = $sessionHandlerFile->close();

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCallWriteMustReturnFalseIfNoDataPassed(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $result = $sessionHandlerFile->write(static::SESSION_ID, '');

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCallWriteMustReturnTrueWhenDataCanBeWrittenToFile(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $result = $sessionHandlerFile->write(static::SESSION_ID, static::SESSION_DATA);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testWriteMustAllowZeroValue(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $result = $sessionHandlerFile->write(static::SESSION_ID, '0');

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCallReadMustReturnContentOfSessionForGivenSessionId(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $sessionHandlerFile->write(static::SESSION_ID, static::SESSION_DATA);

        $result = $sessionHandlerFile->read(static::SESSION_ID);

        $this->assertSame(static::SESSION_DATA, $result);
    }

    /**
     * @return void
     */
    public function testCallDestroyMustReturnTrueIfNoFileExistsForSessionId(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);

        $result = $sessionHandlerFile->destroy(static::SESSION_ID);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCallDestroyMustReturnTrueIfFileExistsForSessionId(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $sessionHandlerFile->write(static::SESSION_ID, static::SESSION_DATA);

        $result = $sessionHandlerFile->destroy(static::SESSION_ID);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCallGcMustDeleteFilesWhichAreOlderThenMaxLifetime(): void
    {
        $sessionHandlerFile = new SessionHandlerFile($this->getSavePath(), static::LIFETIME, $this->createMonitoringServiceMock());
        $sessionHandlerFile->open($this->getSavePath(), static::SESSION_NAME);
        $sessionHandlerFile->write(static::SESSION_ID, static::SESSION_DATA);
        $this->makeFileOlderThanItIs();
        $sessionHandlerFile->write(static::SESSION_ID_2, static::SESSION_DATA);
        $this->makeFileNewerThanItIs();

        $finder = new Finder();
        $finder->in($this->getSavePath());

        $this->assertCount(2, $finder);

        $sessionHandlerFile->gc(1);
        $this->assertCount(1, $finder);

        unlink($this->getSavePath() . '/session:' . static::SESSION_ID_2);
        rmdir($this->getSavePath());
    }

    /**
     * @return void
     */
    protected function makeFileOlderThanItIs(): void
    {
        touch($this->getSavePath() . DIRECTORY_SEPARATOR . 'session:' . static::SESSION_ID, time() - 200);
    }

    /**
     * @return void
     */
    protected function makeFileNewerThanItIs(): void
    {
        touch($this->getSavePath() . DIRECTORY_SEPARATOR . 'session:' . static::SESSION_ID_2, time() + 200);
    }

    /**
     * @return \Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceBridge
     */
    protected function createMonitoringServiceMock(): SessionFileToMonitoringServiceBridge
    {
        /** @var \Spryker\Service\Monitoring\MonitoringServiceInterface|\PHPUnit\Framework\MockObject\MockObject $mock */
        $mock = $this->getMockBuilder(MonitoringServiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sessionToMonitoringServiceBridge = new SessionFileToMonitoringServiceBridge($mock);

        return $sessionToMonitoringServiceBridge;
    }
}
