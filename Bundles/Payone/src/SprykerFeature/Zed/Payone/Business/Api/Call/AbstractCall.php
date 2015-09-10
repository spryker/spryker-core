<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Call;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Key\HashGeneratorInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
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
     * @param AbstractRequestContainer $container
     */
    protected function applyStandardParameter(AbstractRequestContainer $container)
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
        if (null === $container->getApiVersion()) {
            $container->setApiVersion($this->standardParameter->getApiVersion());
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
