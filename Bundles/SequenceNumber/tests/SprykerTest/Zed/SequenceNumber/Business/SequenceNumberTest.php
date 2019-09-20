<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SequenceNumber\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Spryker\Zed\SequenceNumber\Business\Model\SequenceNumber;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;
use Spryker\Zed\SequenceNumber\SequenceNumberConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SequenceNumber
 * @group Business
 * @group SequenceNumberTest
 * Add your own group annotations below this line
 */
class SequenceNumberTest extends Unit
{
    /**
     * @var \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade
     */
    protected $sequenceNumberFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->sequenceNumberFacade = new SequenceNumberFacade();
    }

    /**
     * @return void
     */
    public function testGetDefaultSettingsMergedWithCustomSettings()
    {
        $customSettings = new SequenceNumberSettingsTransfer();
        $customSettings->setIncrementMinimum(2);
        $customSettings->setOffset(null);

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings($customSettings);

        $this->assertSame(2, $sequenceNumberSettings->getIncrementMinimum());
        $this->assertSame(0, $sequenceNumberSettings->getOffset());
    }

    /**
     * @return void
     */
    public function testGenerate()
    {
        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();

        $sequenceNumber = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('1', $sequenceNumber);

        $number = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('2', $number);

        $sequenceNumberSettings->setOffset(100);
        $number = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('100', $number);

        $sequenceNumberSettings->setOffset(10);
        $number = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('101', $number);
    }

    /**
     * @return void
     */
    public function testGenerateWithPrefix()
    {
        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setPrefix('DE');

        $sequenceNumber = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('DE1', $sequenceNumber);
    }

    /**
     * @return void
     */
    public function testGenerateOnSequenceNumber()
    {
        $generator = $this->generateGenerator();

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setOffset(10);
        $sequenceNumberSettings->setPadding(3);

        $sequenceNumber = new SequenceNumber(
            $generator,
            $sequenceNumberSettings,
            Propel::getConnection()
        );

        $number = $sequenceNumber->generate();
        $this->assertSame('011', $number);

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setName('Other');
        $sequenceNumberSettings->setOffset(2);

        $sequenceNumberOther = new SequenceNumber(
            $generator,
            $sequenceNumberSettings,
            Propel::getConnection()
        );

        $number = $sequenceNumberOther->generate();
        $this->assertSame('3', $number);

        $number = $sequenceNumber->generate();
        $this->assertSame('012', $number);
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\SequenceNumberConfig
     */
    protected function generateConfig()
    {
        $config = new SequenceNumberConfig();

        return $config;
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator
     */
    protected function generateGenerator($min = 1, $max = 1)
    {
        return new RandomNumberGenerator(
            $min,
            $max
        );
    }
}
