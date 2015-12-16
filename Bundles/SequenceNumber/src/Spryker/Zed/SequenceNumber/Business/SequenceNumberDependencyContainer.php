<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Business;

use Spryker\Zed\SequenceNumber\Business\Model\SequenceNumber;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use Spryker\Zed\SequenceNumber\Business\Model\SequenceNumberInterface;
use Spryker\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainerInterface;
use Spryker\Zed\SequenceNumber\SequenceNumberConfig;
use Propel\Runtime\Propel;

/**
 * @method SequenceNumberConfig getConfig()
 * @method SequenceNumberQueryContainerInterface getQueryContainer()
 */
class SequenceNumberDependencyContainer extends AbstractBusinessFactory
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
