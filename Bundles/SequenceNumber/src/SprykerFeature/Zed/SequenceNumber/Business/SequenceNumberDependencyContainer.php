<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use SprykerFeature\Zed\SequenceNumber\Business\Model\SequenceNumber;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use SprykerFeature\Zed\SequenceNumber\Business\Model\SequenceNumberInterface;
use SprykerFeature\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainerInterface;
use SprykerFeature\Zed\SequenceNumber\SequenceNumberConfig;
use Propel\Runtime\Propel;

/**
 * @method SequenceNumberConfig getConfig()
 * @method SequenceNumberQueryContainerInterface getQueryContainer()
 */
class SequenceNumberDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param int $min
     * @param int $max
     *
     * @return RandomNumberGeneratorInterface
     */
    public function createRandomNumberGenerator($min = 1, $max = 1)
    {
        return new RandomNumberGenerator(
                $min,
                $max
            );
    }

    /**
     * @param SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return SequenceNumberInterface
     */
    public function createSequenceNumber(SequenceNumberSettingsTransfer $sequenceNumberSettings)
    {
        $settings = $this->getConfig()->getDefaultSettings($sequenceNumberSettings);

        $generator = $this->createRandomNumberGenerator($settings->getIncrementMinimum(), $settings->getIncrementMaximum());

        return new SequenceNumber(
                $generator,
                $settings,
                Propel::getConnection()
            );
    }

}
