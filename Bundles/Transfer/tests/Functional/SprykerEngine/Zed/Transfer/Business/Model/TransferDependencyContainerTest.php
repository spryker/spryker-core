<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Transfer\Business\TransferDependencyContainer;
use SprykerEngine\Zed\Transfer\TransferConfig;

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
        $factory = new Factory('Transfer');
        $config = new TransferConfig(Config::getInstance(), Locator::getInstance());

        return new TransferDependencyContainer($factory, Locator::getInstance(), $config);
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
