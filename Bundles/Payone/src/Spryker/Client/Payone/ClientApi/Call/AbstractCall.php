<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payone\ClientApi\Call;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Spryker\Client\Payone\ClientApi\HashGeneratorInterface;
use Spryker\Client\Payone\ClientApi\Request\AbstractRequest;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;

abstract class AbstractCall
{

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \Spryker\Client\Payone\ClientApi\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @var \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameterTransfer
     * @param \Spryker\Client\Payone\ClientApi\HashGeneratorInterface $hashGenerator
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
     * @param \Spryker\Client\Payone\ClientApi\Request\AbstractRequest $container
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
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        return $this->standardParameter;
    }

    /**
     * @return \Spryker\Client\Payone\ClientApi\HashGeneratorInterface
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
