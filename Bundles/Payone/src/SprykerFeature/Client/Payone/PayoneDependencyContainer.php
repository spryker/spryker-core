<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Zed\Ide\Payone;
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
     * @param array $defaults
     *
     * @return HashGeneratorInterface
     */
    public function createCreditCardCheckCall(array $defaults)
    {
        return $this->getFactory()->createClientApiCallCreditCardCheck(
            $this->createStandardParameter($defaults),
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
     * @param array $defaults
     *
     * @return PayoneStandardParameterTransfer
     */
    protected function createStandardParameter(array $defaults)
    {
        $standardParameterTransfer = new PayoneStandardParameterTransfer();
        $standardParameterTransfer->fromArray($defaults);

        /********************************
         * @todo get params from config (like in PayoneConfig zed bundle)
         ********************************/

        return $standardParameterTransfer;
    }

}
