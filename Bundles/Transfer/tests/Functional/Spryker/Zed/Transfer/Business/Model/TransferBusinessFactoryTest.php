<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Transfer\Business\Model\TransferCleaner;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Spryker\Zed\Transfer\Business\Model\TransferValidatorInterface;
use Spryker\Zed\Transfer\Business\TransferBusinessFactory;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group TransferBusinessFactoryTest
 */
class TransferBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferBusinessFactory
     */
    private function getFactory()
    {
        return new TransferBusinessFactory();
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
    public function testCreateTransferGeneratorShouldReturnFullyConfiguredInstance()
    {
        $transferGenerator = $this->getFactory()->createTransferGenerator(
            $this->getMessenger()
        );

        $this->assertInstanceOf(TransferGenerator::class, $transferGenerator);
    }

    /**
     * @return void
     */
    public function testCreateTransferCleanerShouldReturnFullyConfiguredInstance()
    {
        $transferCleaner = $this->getFactory()->createTransferCleaner();

        $this->assertInstanceOf(TransferCleaner::class, $transferCleaner);
    }

    /**
     * @return void
     */
    public function testCreateTransferValidatorShouldReturnFullyConfiguredInstance()
    {
        $transferCleaner = $this->getFactory()->createValidator($this->getMessenger());

        $this->assertInstanceOf(TransferValidatorInterface::class, $transferCleaner);
    }

}
