<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone;

use Generated\Client\Ide\FactoryAutoCompletion\Payone;
use Generated\Shared\Transfer\StandardParameterTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Payone\ClientApi\HashGeneratorInterface;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @method Payone getFactory()
 */
class PayoneDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return HashGeneratorInterface
     */
    public function createCreditCardCheckCall()
    {
        return $this->getFactory()->createClientApiCallCreditCardCheck(
            $this->createStandardParameter(),
            $this->createHashProvider(),
            $this->createHashGenerator(),
            $this->createModeDetector()
        );
    }

    /**
     * @return HashInterface
     */
    protected function createHashProvider()
    {
        return $this->getFactory()->createClientApiHashProvider();
    }

    /**
     * @return ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return $this->getFactory()->createClientApiModeModeDetector();
    }

    /**
     * @return HashGeneratorInterface
     */
    protected function createHashGenerator()
    {
        return $this->getFactory()->createClientApiHashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @return StandardParameterTransfer
     */
    protected function createStandardParameter()
    {
        $standardParameter = new StandardParameterTransfer();

        /********************************
         * @todo get params from config (like in PayoneConfig zed bundle)
         ********************************/

        return $standardParameter;
    }

}
