<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Transfer\Business\TransferFacade;
use SprykerEngine\Zed\Transfer\TransferConfig;
use Symfony\Component\Finder\Finder;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferFacade
 */
class TransferFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return TransferFacade
     */
    private function getFacade()
    {
        return new TransferFacade();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|MessengerInterface
     */
    private function getMessenger()
    {
        return $this->getMock('SprykerEngine\Shared\Kernel\Messenger\MessengerInterface');
    }

    /**
     * @return void
     */
    public function testDeleteGeneratedTransferObjectsShouldDeleteAllGeneratedTransferObjects()
    {
        $this->getFacade()->deleteGeneratedTransferObjects();

        $this->assertFalse(is_dir($this->getConfig()->getGeneratedTargetDirectory()));
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
        $finder->in($this->getConfig()->getGeneratedTargetDirectory())->name('*Transfer.php');

        $this->assertTrue($finder->count() > 0);
    }

    /**
     * @return TransferConfig
     */
    private function getConfig()
    {
        return new TransferConfig();
    }

}
