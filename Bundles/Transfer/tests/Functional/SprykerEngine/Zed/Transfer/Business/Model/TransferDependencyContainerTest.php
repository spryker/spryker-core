<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Transfer\Business\TransferDependencyContainer;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferDependencyContainer
 */
class TransferDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return TransferDependencyContainer
     */
    private function getDependencyContainer()
    {
        return new TransferDependencyContainer();
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
    public function testCreateTransferGeneratorShouldReturnFullyConfiguredInstance()
    {
        $transferGenerator = $this->getDependencyContainer()->createTransferGenerator(
            $this->getMessenger()
        );

        $this->assertInstanceOf('SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator', $transferGenerator);
    }

    /**
     * @return void
     */
    public function testCreateTransferCleanerShouldReturnFullyConfiguredInstance()
    {
        $transferCleaner = $this->getDependencyContainer()->createTransferCleaner();

        $this->assertInstanceOf('SprykerEngine\Zed\Transfer\Business\Model\TransferCleaner', $transferCleaner);
    }

}
