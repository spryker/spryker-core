<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Call;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use SprykerFeature\Client\Payone\ClientApi\HashGeneratorInterface;
use SprykerFeature\Client\Payone\ClientApi\Request\AbstractRequest;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall
{

    /**
     * @var PayoneStandardParameterInterface
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
     * @param PayoneStandardParameterInterface $standardParameterTransfer
     * @param HashGeneratorInterface $hashGenerator
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        PayoneStandardParameterInterface $standardParameterTransfer,
        HashGeneratorInterface $hashGenerator,
        ModeDetectorInterface $modeDetector
    ) {
        $this->standardParameter = $standardParameterTransfer;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param AbstractRequest $container
     */
    protected function applyStandardParameter(AbstractRequest $container)
    {
        if (null === $container->getPortalid()) {
            $container->setPortalid($this->standardParameter->getPortalId());
        }
        if (null === $container->getAid()) {
            $container->setAid($this->standardParameter->getAid());
        }
        if (null === $container->getMid()) {
            $container->setMid($this->standardParameter->getMid());
        }
        if (null === $container->getEncoding()) {
            $container->setEncoding($this->standardParameter->getEncoding());
        }
        if (null === $container->getMode()) {
            $container->setMode($this->modeDetector->getMode());
        }
        if (null === $container->getLanguage()) {
            $container->setLanguage($this->standardParameter->getLanguage());
        }
    }

    /**
     * @return PayoneStandardParameterInterface
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
