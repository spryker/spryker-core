<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\TransferValidator;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group TransferValidatorTest
 * Add your own group annotations below this line
 */
class TransferValidatorTest extends Unit
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @return void
     */
    public function testValidate()
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Test/Transfer/'),
        ];
        $definitionFinder = $this->getDefinitionFinder($sourceDirectories);
        $messenger = $this->getMessengerMock();
        $config = new TransferConfig();
        $transferValidator = new TransferValidator($messenger, $definitionFinder, $config);

        $options = [
            'bundle' => null,
            'verbose' => true,
        ];
        $result = $transferValidator->validate($options);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testValidateWithBC()
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Error/Transfer/'),
        ];
        $definitionFinder = $this->getDefinitionFinder($sourceDirectories);
        $messenger = $this->getMessengerMock();
        $config = new TransferConfig();
        $transferValidator = new TransferValidator($messenger, $definitionFinder, $config);

        $options = [
            'bundle' => null,
            'verbose' => true,
        ];
        $result = $transferValidator->validate($options);

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testValidateWithoutBC()
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Error/Transfer/'),
        ];
        $definitionFinder = $this->getDefinitionFinder($sourceDirectories);
        $messenger = $this->getMessengerMock();
        $config = $this->getTransferConfigMock();
        $config->expects($this->any())->method('useStrictGeneration')->willReturn(true);

        $transferValidator = new TransferValidator($messenger, $definitionFinder, $config);

        $options = [
            'bundle' => null,
            'verbose' => true,
        ];
        $result = $transferValidator->validate($options);

        $this->assertFalse($result);
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder
     */
    protected function getDefinitionFinder($sourceDirectories)
    {
        $this->output = new ConsoleOutput();
        $definitionFinder = new TransferDefinitionFinder(
            $sourceDirectories
        );

        return $definitionFinder;
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMessengerMock()
    {
        return $this->getMockBuilder(ConsoleLogger::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Transfer\TransferConfig
     */
    protected function getTransferConfigMock()
    {
        return $this->getMockBuilder(TransferConfig::class)->setMethods(['useStrictGeneration'])->getMock();
    }
}
