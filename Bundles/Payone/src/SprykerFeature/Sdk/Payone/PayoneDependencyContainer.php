<?php

namespace SprykerFeature\Sdk\Payone;


use Generated\Sdk\Ide\FactoryAutoCompletion\Payone;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Payone\ClientApi\HashGeneratorInterface;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

/**
 * @method Payone getFactory()
 */
class PayoneDependencyContainer extends AbstractDependencyContainer
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
     * @return PayoneStandardParameterTransfer
     */
    protected function createStandardParameter()
    {
        $standardParameter = new PayoneStandardParameterTransfer();

        /********************************
         * @todo get params from config (like in PayoneConfig zed bundle)
         ********************************/

        return $standardParameter;
    }

}
