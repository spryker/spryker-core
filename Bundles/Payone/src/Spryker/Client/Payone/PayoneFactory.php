<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payone;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Payone\ClientApi\Call\CreditCardCheck;
use Spryker\Client\Payone\ClientApi\HashGenerator;
use Spryker\Client\Payone\ClientApi\HashGeneratorInterface;
use Spryker\Client\Payone\ClientApi\HashProvider;
use Spryker\Client\Payone\ClientApi\Mode\ModeDetector;
use Spryker\Shared\Payone\Dependency\HashInterface;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;

class PayoneFactory extends AbstractFactory
{

    /**
     * @param array $defaults
     *
     * @return \Spryker\Client\Payone\ClientApi\HashGeneratorInterface
     */
    public function createCreditCardCheckCall(array $defaults)
    {
        return new CreditCardCheck(
            $this->createStandardParameter($defaults),
            $this->createHashGenerator(),
            $this->createModeDetector()
        );
    }

    /**
     * @return \Spryker\Shared\Payone\Dependency\HashInterface
     */
    protected function createHashProvider()
    {
        return new HashProvider();
    }

    /**
     * @return \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return new ModeDetector();
    }

    /**
     * @return \Spryker\Client\Payone\ClientApi\HashGeneratorInterface
     */
    protected function createHashGenerator()
    {
        return new HashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @param array $defaults
     *
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
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
