<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Transfer\Business\TransferFacade;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Finder\Finder;

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
     * @return \Spryker\Zed\Transfer\Business\TransferFacade
     */
    private function getFacade()
    {
        return new TransferFacade();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Psr\Log\LoggerInterface
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
        $this->getFacade()->deleteGeneratedTransferObjects();

        $finder = new Finder();
        $finder->in($this->getConfig()->getClassTargetDirectory())->name('*Transfer.php')->files();

        $this->assertCount(0, $finder, 'Directory containing generated transfer object files is not empty');
    }

    /**
     * @depends testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects
     *
     * @return void
     */
    public function testGenerateTransferObjectsShouldGenerateTransferObjects()
    {
        $this->getFacade()->generateTransferObjects($this->getMessenger());

        $finder = new Finder();
        $finder->in($this->getConfig()->getClassTargetDirectory())->name('*Transfer.php');

        $this->assertTrue($finder->count() > 0);
    }

    /**
     * @depends testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects
     *
     * @return void
     */
    public function testValidateTransferObjectsShouldValidateTransferObjects()
    {
        $result = $this->getFacade()->validateTransferObjects($this->getMessenger(), ['bundle' => false, 'verbose' => false]);

        $this->assertTrue($result);
    }

    /**
     * @return \Spryker\Zed\Transfer\TransferConfig
     */
    private function getConfig()
    {
        return new TransferConfig();
    }
}
