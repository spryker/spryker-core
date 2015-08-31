<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Payone\ClientApi\Call;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use SprykerFeature\Client\Payone\ClientApi\HashGeneratorInterface;
use SprykerFeature\Client\Payone\ClientApi\Request\AbstractRequest;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall
{

    /**
     * @var PayoneStandardParameterInterface
     */
    protected $standardParameter;
    /**
     * @var HashInterface
     */
    protected $hashProvider;
    /**
     * @var HashGeneratorInterface
     */
    protected $hashGenerator;
    /**
     * @var ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param PayoneStandardParameterInterface $standardParameter
     * @param HashInterface $hashProvider
     * @param HashGeneratorInterface $hashGenerator
     * @param ModeDetectorInterface $modeDetector
     */
    public function __construct(
        PayoneStandardParameterInterface $standardParameterTransfer,
        HashInterface $hashProvider,
        HashGeneratorInterface $hashGenerator,
        ModeDetectorInterface $modeDetector
    )
    {
        $this->standardParameter = $standardParameterTransfer;
        $this->hashProvider = $hashProvider;
        $this->hashGenerator = $hashGenerator;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param AbstractRequest $container
     */
    protected function applyStandardParameter(AbstractRequest $container)
    {
        $container->setPortalid($this->standardParameter->getPortalId());
        $container->setAid($this->standardParameter->getAid());
        $container->setMid($this->standardParameter->getMid());
        $container->setEncoding($this->standardParameter->getEncoding());
        $container->setMode($this->modeDetector->getMode());
        $container->setLanguage($this->standardParameter->getLanguage());
    }

    /**
     * @return HashInterface
     */
    protected function getHashProvider()
    {
        return $this->hashProvider;
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
