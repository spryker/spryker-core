<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\SequenceNumber;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Spryker\Zed\SequenceNumber\Business\Model\SequenceNumber;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;
use Spryker\Zed\SequenceNumber\SequenceNumberConfig;

/**
 * @group SequenceNumberTest
 */
class SequenceNumberTest extends Test
{

    /**
     * @var SequenceNumberFacade
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
        $customSettings->setMinimumNumber(null);

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings($customSettings);

        $this->assertSame(2, $sequenceNumberSettings->getIncrementMinimum());
        $this->assertSame(1, $sequenceNumberSettings->getMinimumNumber());
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

        $sequenceNumberSettings->setMinimumNumber(100);
        $number = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('100', $number);

        $sequenceNumberSettings->setMinimumNumber(10);
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
        $sequenceNumberSettings->setMinimumNumber(10);
        $sequenceNumberSettings->setPadding(3);

        $sequenceNumber = new SequenceNumber(
            $generator,
            $sequenceNumberSettings,
            Propel::getConnection()
        );

        $number = $sequenceNumber->generate();
        $this->assertSame('010', $number);

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setName('Other');
        $sequenceNumberSettings->setMinimumNumber(2);

        $sequenceNumberOther = new SequenceNumber(
            $generator,
            $sequenceNumberSettings,
            Propel::getConnection()
        );

        $number = $sequenceNumberOther->generate();
        $this->assertSame('2', $number);

        $number = $sequenceNumber->generate();
        $this->assertSame('011', $number);
    }

    /**
     * @return SequenceNumberConfig
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
     * @return RandomNumberGenerator
     */
    protected function generateGenerator($min = 1, $max = 1)
    {
        return new RandomNumberGenerator(
            $min,
            $max
        );
    }

}
