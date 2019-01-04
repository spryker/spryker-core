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
     * @return \PHPUnit\Framework\MockObject\MockObject|\PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
     */
    private function getMessenger()
    {
        return $this->getMockBuilder(LoggerInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects()
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
    public function testGenerateTransferObjectsShouldGenerateTransferObjects()
    {
        $this->generateTransfers();
        $this->tester->assertVirtualDirectoryNotEmpty($this->tester->getTransferDestinationDir(), 'Transfers weren\'t generated successfully');
    }

    /**
     * @depends testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects
     *
     * @return void
     */
    public function testValidateTransferObjectsShouldValidateTransferObjects()
    {
        $result = $this->tester->getFacade()->validateTransferObjects($this->getMessenger(), ['bundle' => false, 'verbose' => false]);
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    protected function generateTransfers(): void
    {
        $this->tester->getFacade()->generateTransferObjects($this->getMessenger());
        $this->tester->getFacade()->generateEntityTransferObjects($this->getMessenger());
    }
}
