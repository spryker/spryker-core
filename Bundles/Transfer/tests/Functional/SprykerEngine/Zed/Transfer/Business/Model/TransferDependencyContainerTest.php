<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Transfer\Business\TransferDependencyContainer;

/**
 * @group Spryker
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
        return $this->getMock('Spryker\Shared\Kernel\Messenger\MessengerInterface');
    }

    /**
     * @return void
     */
    public function testCreateTransferGeneratorShouldReturnFullyConfiguredInstance()
    {
        $transferGenerator = $this->getDependencyContainer()->createTransferGenerator(
            $this->getMessenger()
        );

        $this->assertInstanceOf('Spryker\Zed\Transfer\Business\Model\TransferGenerator', $transferGenerator);
    }

    /**
     * @return void
     */
    public function testCreateTransferCleanerShouldReturnFullyConfiguredInstance()
    {
        $transferCleaner = $this->getDependencyContainer()->createTransferCleaner();

        $this->assertInstanceOf('Spryker\Zed\Transfer\Business\Model\TransferCleaner', $transferCleaner);
    }

}
