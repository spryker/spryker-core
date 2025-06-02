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
use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface;

class CodeGenerator implements CodeGeneratorInterface
{
    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const EXPIRATION_INTERVAL_FORMAT = 'PT%dM';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface $configProvider
     */
    public function __construct(
        protected CodeGeneratorConfigProviderInterface $configProvider
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function generateCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthTransfer
    {
        $code = $this->generateNumericCode();

        $expirationDate = $this->getExpirationDate();

        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($code)
            ->setExpirationDate($expirationDate->format(static::DATE_FORMAT));

        return $multiFactorAuthTransfer->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);
    }

    /**
     * @return string
     */
    protected function generateNumericCode(): string
    {
        $min = (int)str_pad('1', $this->configProvider->getCodeLength(), '0', STR_PAD_RIGHT);
        $max = (int)str_pad('', $this->configProvider->getCodeLength(), '9');

        return (string)random_int($min, $max);
    }

    /**
     * @return \DateTime
     */
    protected function getExpirationDate(): DateTime
    {
        return (new DateTime())->add(
            new DateInterval(sprintf(static::EXPIRATION_INTERVAL_FORMAT, $this->configProvider->getCodeValidityTtl())),
        );
    }
}
