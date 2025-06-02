<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Sender\User;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;

class UserCodeSender implements CodeSenderInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface $codeGenerator
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface> $sendStrategies
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
        $multiFactorAuthTransfer = $this->codeGenerator->generateCode($multiFactorAuthTransfer);

        $this->entityManager->saveUserCode($multiFactorAuthTransfer);

        foreach ($this->sendStrategies as $sendStrategy) {
            if ($sendStrategy->isApplicable($multiFactorAuthTransfer)) {
                return $sendStrategy->send($multiFactorAuthTransfer);
            }
        }

        return $multiFactorAuthTransfer;
    }
}
