<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use SprykerFeature\Zed\SequenceNumber\Business\Model\SequenceNumberInterface;
use SprykerFeature\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainerInterface;
use SprykerFeature\Zed\SequenceNumber\SequenceNumberConfig;
use Propel\Runtime\Propel;

/**
 * @method SequenceNumberConfig getConfig()
 * @method SequenceNumberBusiness getFactory()
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
        return $this->getFactory()
            ->createGeneratorRandomNumberGenerator(
                $min,
                $max
            )
        ;
    }

    /**
     * @param SequenceNumberSettingsInterface $sequenceNumberSettings
     *
     * @return SequenceNumberInterface
     */
    public function createSequenceNumber(SequenceNumberSettingsInterface $sequenceNumberSettings)
    {
        $settings = $this->getConfig()->getDefaultSettings();
        $settings->fromArray($sequenceNumberSettings->toArray());

        $generator = $this->createRandomNumberGenerator($settings->getIncrementMinimum(), $settings->getIncrementMaximum());

        return $this->getFactory()
            ->createModelSequenceNumber(
                $generator,
                $settings,
                Propel::getConnection()
            )
        ;
    }

}
