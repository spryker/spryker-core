<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Facade
 * @group TransferFacadeTest
 * Add your own group annotations below this line
 */
class TransferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Transfer\TransferBusinessTester
     */
    protected $tester;

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private function getMessenger(): LoggerInterface
    {
        return $this->getMockBuilder(LoggerInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects(): void
    {
        $this->generateTransfers();
        $this->tester->getFacade()->deleteGeneratedTransferObjects();
        $this->tester->assertVirtualDirectoryIsEmpty($this->tester->getTransferDestinationDir(), 'Directory containing generated transfer object files is not empty');
    }

    /**
     * @depends testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects
     *
     * @return void
     */
    public function testGenerateTransferObjectsShouldGenerateTransferObjects(): void
    {
        $this->generateTransfers();
        $this->tester->assertVirtualDirectoryNotEmpty($this->tester->getTransferDestinationDir(), 'Transfers weren\'t generated successfully');
    }

    /**
     * @depends testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects
     *
     * @return void
     */
    public function testValidateTransferObjectsShouldValidateTransferObjects(): void
    {
        $result = $this->tester->getFacade()->validateTransferObjects($this->getMessenger(), ['bundle' => false, 'verbose' => false]);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanGenerateDataTransfers(): void
    {
        // Arrange
        $transferDestinationDirectory = $this->tester->getTransferDestinationDir();

        // Act
        $this->generateDataTransfers();

        // Assert
        $this->tester->assertVirtualDirectoryNotEmpty($transferDestinationDirectory);
        $this->tester->isDataTransfersExist($transferDestinationDirectory);
    }

    /**
     * @return void
     */
    public function testCanGenerateEntityTransfers(): void
    {
        // Arrange
        $transferDestinationDirectory = $this->tester->getTransferDestinationDir();

        // Act
        $this->generateEntityTransfers();

        // Assert
        $this->tester->assertVirtualDirectoryNotEmpty($transferDestinationDirectory);
        $this->tester->isEntityTransfersExist($transferDestinationDirectory);
    }

    /**
     * @return void
     */
    public function testCanDeleteDataTransfers(): void
    {
        // Arrange
        $transferDestinationDirectory = $this->tester->getTransferDestinationDir();

        // Act
        $this->generateTransfers();
        $this->tester->getFacade()->deleteGeneratedDataTransferObjects();

        // Assert
        $this->assertTrue(
            $this->tester->isEntityTransfersExist($transferDestinationDirectory)
        );
        $this->assertFalse(
            $this->tester->isDataTransfersExist($transferDestinationDirectory)
        );
    }

    /**
     * @return void
     */
    public function testCanDeleteEntityTransfers(): void
    {
        // Arrange
        $this->generateTransfers();

        // Act
        $this->tester->getFacade()->deleteGeneratedEntityTransferObjects();

        // Assert
        $this->assertTrue($this->tester->isDataTransfersExist(
            $this->tester->getTransferDestinationDir()
        ));
        $this->assertFalse($this->tester->isEntityTransfersExist(
            $this->tester->getTransferDestinationDir()
        ));
    }

    /**
     * @return void
     */
    protected function generateTransfers(): void
    {
        $this->generateDataTransfers();
        $this->generateEntityTransfers();
    }

    /**
     * @return void
     */
    protected function generateDataTransfers(): void
    {
        $this->tester->getFacade()->generateTransferObjects($this->getMessenger());
    }

    /**
     * @return void
     */
    protected function generateEntityTransfers(): void
    {
        $this->tester->getFacade()->generateEntityTransferObjects($this->getMessenger());
    }
}
