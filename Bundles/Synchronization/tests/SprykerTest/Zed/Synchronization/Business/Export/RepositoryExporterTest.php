<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business\Export;

use Codeception\Test\Unit;
use Spryker\Zed\Synchronization\Business\Export\ExporterInterface;
use Spryker\Zed\Synchronization\Business\Export\RepositoryExporter;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\Synchronization\Dependency\Facade\SynchronizationToStoreFacadeInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Business
 * @group Export
 * @group RepositoryExporterTest
 * Add your own group annotations below this line
 */
class RepositoryExporterTest extends Unit
{
    /**
     * @return void
     */
    public function testExportSynchronizedDataWillNotDisablePoolingWithDisableInstantPoolingParamFalse(): void
    {
        // Arrange
        $repositoryExporterMock = $this->createRepositoryExporterMock(false);
        $repositoryExporterMock->expects($this->never())->method('disableInstancePooling');
        $repositoryExporterMock->expects($this->never())->method('enableInstancePooling');

        // Act, Assert
        $repositoryExporterMock->exportSynchronizedData([], []);
    }

    /**
     * @return void
     */
    public function testExportSynchronizedDataWillDisablePoolingWithDisableInstantPoolingParamTrue(): void
    {
        // Arrange
        $repositoryExporterMock = $this->createRepositoryExporterMock(true);
        $repositoryExporterMock->expects($this->once())->method('disableInstancePooling')->willReturn(true);
        $repositoryExporterMock->expects($this->once())->method('enableInstancePooling');

        // Act, Assert
        $repositoryExporterMock->exportSynchronizedData([], []);
    }

    /**
     * @param bool $disablePropelInstancePool
     *
     * @return \Spryker\Zed\Synchronization\Business\Export\ExporterInterface
     */
    protected function createRepositoryExporterMock(bool $disablePropelInstancePool): ExporterInterface
    {
        $synchronizationConfigStub = $this->getMockBuilder(SynchronizationConfig::class)
            ->onlyMethods(['isRepositorySyncExportPropelInstancePoolingDisabled'])->getMock();
        $synchronizationConfigStub->method('isRepositorySyncExportPropelInstancePoolingDisabled')->willReturn($disablePropelInstancePool);

        return $this->getMockBuilder(RepositoryExporter::class)
            ->setConstructorArgs([
                    $this->getMockBuilder(SynchronizationToQueueClientInterface::class)->getMock(),
                    $this->getMockBuilder(SynchronizationToStoreFacadeInterface::class)->getMock(),
                    $this->getMockBuilder(QueueMessageCreatorInterface::class)->getMock(),
                    $this->getMockBuilder(SynchronizationToUtilEncodingServiceInterface::class)->getMock(),
                    $synchronizationConfigStub,
            ])
            ->onlyMethods(['disableInstancePooling', 'enableInstancePooling'])
            ->getMock();
    }
}
