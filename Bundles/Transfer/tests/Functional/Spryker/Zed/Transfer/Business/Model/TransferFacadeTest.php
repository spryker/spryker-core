<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Transfer\Business\TransferFacade;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Finder\Finder;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group TransferFacadeTest
 */
class TransferFacadeTest extends \PHPUnit_Framework_TestCase
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
        return $this->getMock(MessengerInterface::class);
    }

    /**
     * @return void
     */
    public function testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects()
    {
        $this->getFacade()->deleteGeneratedTransferObjects();

        $this->assertFalse(is_dir($this->getConfig()->getClassTargetDirectory()));
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
