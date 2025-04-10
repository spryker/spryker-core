<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Sender\Customer;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;

class CustomerCodeSender implements CodeSenderInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface $codeGenerator
     * @param array<\Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface> $sendStrategies
     */
    public function __construct(
        protected MultiFactorAuthEntityManagerInterface $entityManager,
        protected CodeGeneratorInterface $codeGenerator,
        protected array $sendStrategies
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function sendCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        $this->entityManager->saveCustomerCode(
            $this->codeGenerator->generateCode($multiFactorAuthTransfer),
        );

        foreach ($this->sendStrategies as $sendStrategy) {
            if ($sendStrategy->isApplicable($multiFactorAuthTransfer)) {
                return $sendStrategy->send($multiFactorAuthTransfer);
            }
        }

        return $multiFactorAuthTransfer;
    }
}
