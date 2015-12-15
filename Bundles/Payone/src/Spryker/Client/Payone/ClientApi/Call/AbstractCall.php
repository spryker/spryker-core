<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payone\ClientApi\Call;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Client\Payone\ClientApi\HashGeneratorInterface;
use Spryker\Client\Payone\ClientApi\Request\AbstractRequest;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall
{

    /**
     * @var PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param PayoneStandardParameterTransfer $standardParameterTransfer
     * @param HashGeneratorInterface $hashGenerator
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        PayoneStandardParameterTransfer $standardParameterTransfer,
        HashGeneratorInterface $hashGenerator,
        ModeDetectorInterface $modeDetector
    ) {
        $this->standardParameter = $standardParameterTransfer;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param AbstractRequest $container
     *
     * @return void
     */
    protected function applyStandardParameter(AbstractRequest $container)
    {
        if ($container->getPortalid() === null) {
            $container->setPortalid($this->standardParameter->getPortalId());
        }
        if ($container->getAid() === null) {
            $container->setAid($this->standardParameter->getAid());
        }
        if ($container->getMid() === null) {
            $container->setMid($this->standardParameter->getMid());
        }
        if ($container->getEncoding() === null) {
            $container->setEncoding($this->standardParameter->getEncoding());
        }
        if ($container->getMode() === null) {
            $container->setMode($this->modeDetector->getMode());
        }
        if ($container->getLanguage() === null) {
            $container->setLanguage($this->standardParameter->getLanguage());
        }
    }

    /**
     * @return PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @return HashGeneratorInterface
     */
    protected function getHashGenerator()
    {
        return $this->hashGenerator;
    }

    /**
     * @return ModeDetectorInterface
     */
    protected function getModeDetector()
    {
        return $this->modeDetector;
    }

}
