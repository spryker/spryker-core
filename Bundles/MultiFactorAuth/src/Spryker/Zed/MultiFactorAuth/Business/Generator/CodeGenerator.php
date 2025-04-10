<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Generator;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;

class CodeGenerator implements CodeGeneratorInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(protected MultiFactorAuthConfig $config)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function generateCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        $min = (int)str_pad('1', $this->config->getCustomerCodeLength(), '0', STR_PAD_RIGHT);
        $max = (int)str_pad('', $this->config->getCustomerCodeLength(), '9');

        $generatedCode = (string)random_int($min, $max);

        $expirationTime = (new DateTime())->add(
            new DateInterval(sprintf('PT%dM', $this->config->getCustomerCodeValidityTtl())),
        );

        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setExpirationDate($expirationTime->format('Y-m-d H:i:s'))
            ->setCode($generatedCode);

        $multiFactorAuthTransfer->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $multiFactorAuthTransfer;
    }
}
