<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface;
use SprykerFeature\Zed\SequenceNumber\Business\Model\SequenceNumberInterface;
use SprykerFeature\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainerInterface;
use SprykerFeature\Zed\SequenceNumber\SequenceNumberConfig;

/**
 * @method SequenceNumberConfig getConfig()
 * @method SequenceNumberBusiness getFactory()
 * @method SequenceNumberQueryContainerInterface getQueryContainer()
 */
class SequenceNumberDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return RandomNumberGeneratorInterface
     */
    public function createRandomNumberGenerator()
    {
        return $this->getFactory()
            ->createGeneratorRandomNumberGenerator(
                $this->getConfig()->getNumberIncrementMin(),
                $this->getConfig()->getNumberIncrementMax()
            )
        ;
    }

    /**
     * @return SequenceNumberInterface
     */
    public function createSequenceNumber()
    {
        $generator = $this->createRandomNumberGenerator();

        return $this->getFactory()
            ->createModelSequenceNumber(
                $generator,
                $this->getConfig()->getSequenceName(),
                $this->getConfig()->getNumberMinimum(),
                $this->getConfig()->getNumberLength()
            )
        ;
    }

}
