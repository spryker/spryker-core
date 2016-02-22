<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Transfer\Business\TransferBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferBusinessFactory
 */
class TransferBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferBusinessFactory
     */
    private function getFactory()
    {
        return new TransferBusinessFactory();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    private function getMessenger()
    {
        return $this->getMock(MessengerInterface::class);
    }

    /**
     * @return void
     */
    public function testCreateTransferGeneratorShouldReturnFullyConfiguredInstance()
    {
        $transferGenerator = $this->getFactory()->createTransferGenerator(
            $this->getMessenger()
        );

        $this->assertInstanceOf('Spryker\Zed\Transfer\Business\Model\TransferGenerator', $transferGenerator);
    }

    /**
     * @return void
     */
    public function testCreateTransferCleanerShouldReturnFullyConfiguredInstance()
    {
        $transferCleaner = $this->getFactory()->createTransferCleaner();

        $this->assertInstanceOf('Spryker\Zed\Transfer\Business\Model\TransferCleaner', $transferCleaner);
    }

}
