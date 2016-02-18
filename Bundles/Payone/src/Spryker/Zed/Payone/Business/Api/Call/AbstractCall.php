<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Call;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Zed\Payone\Business\Key\HashGeneratorInterface;
use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall
{

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \Spryker\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameterTransfer
     * @param \Spryker\Zed\Payone\Business\Key\HashGeneratorInterface $hashGenerator
     * @param \Spryker\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function applyStandardParameter(AbstractRequestContainer $container)
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
        if ($container->getApiVersion() === null) {
            $container->setApiVersion($this->standardParameter->getApiVersion());
        }
        if ($container->getResponsetype() === null) {
            $container->setResponsetype($this->standardParameter->getResponseType());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    protected function getHashGenerator()
    {
        return $this->hashGenerator;
    }

    /**
     * @return \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function getModeDetector()
    {
        return $this->modeDetector;
    }

}
