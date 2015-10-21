<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\SequenceNumber;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\Propel;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\SequenceNumber\Business\Model\SequenceNumber;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;
use SprykerEngine\Zed\Kernel\Persistence\Factory;
use SprykerFeature\Zed\SequenceNumber\SequenceNumberConfig;

/**
 * @group SequenceNumberTest
 */
class SequenceNumberTest extends Test
{

    /** @var Factory */
    protected $factory;

    /**
     * @var SequenceNumberFacade
     */
    protected $sequenceNumberFacade;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();
        $this->factory = new \SprykerEngine\Zed\Kernel\Business\Factory('SequenceNumber');
        $this->sequenceNumberFacade = new SequenceNumberFacade($this->factory, $locator);
    }

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

    public function testGenerate()
    {
        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();

        $sequenceNumber = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('1', $sequenceNumber);

        $number = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('2', $number);
    }

    public function testGenerateWithPrefix()
    {
        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setPrefix('DE');

        $sequenceNumber = $this->sequenceNumberFacade->generate($sequenceNumberSettings);
        $this->assertSame('DE1', $sequenceNumber);
    }

    public function testGenerateOnSequenceNumber()
    {
        $generator = $this->generateGenerator();

        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setMinimumNumber(10);
        $sequenceNumberSettings->setPadding(3);

        /** @var SequenceNumber $sequenceNumber */
        $sequenceNumber = $this->factory->createModelSequenceNumber(
            $generator,
            $sequenceNumberSettings,
            Propel::getConnection()
        );

        $number = $sequenceNumber->generate();
        $this->assertSame('010', $number);

        // Make sure other sequences don't interfere
        $config = $this->generateConfig();
        $sequenceNumberSettings = $config->getDefaultSettings();
        $sequenceNumberSettings->setName('Other');
        $sequenceNumberSettings->setMinimumNumber(2);

        /** @var SequenceNumber $sequenceNumberOther */
        $sequenceNumberOther = $this->factory->createModelSequenceNumber(
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
        $locator = Locator::getInstance();
        $config = new SequenceNumberConfig(new Config(), $locator);

        return $config;
    }

    protected function generateGenerator($min = 1, $max = 1)
    {
        return $this->factory->createGeneratorRandomNumberGenerator(
            $min,
            $max
        );
    }

}
