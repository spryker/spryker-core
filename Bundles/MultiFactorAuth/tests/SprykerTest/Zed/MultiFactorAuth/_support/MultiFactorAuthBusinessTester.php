<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MultiFactorAuth;

use Codeception\Actor;
use DateTime;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class MultiFactorAuthBusinessTester extends Actor
{
    use _generated\MultiFactorAuthBusinessTesterActions;

    /**
     * @var string
     */
    public const VALID_CODE = '123456';

    /**
     * @var string
     */
    public const INVALID_CODE = '654321';

    /**
     * @var string
     */
    public const TYPE_EMAIL = 'email';

    /**
     * @var string
     */
    public const EXPIRATION_DATE_VALID = '+1 hour';

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTransfer
     */
    public function createMultiFactorAuthTransfer(string $type): MultiFactorAuthTransfer
    {
        $multiFactorAuthTransfer = new MultiFactorAuthTransfer();
        $multiFactorAuthTransfer->setMultiFactorAuthCode((new MultiFactorAuthCodeTransfer())->setCode($code ?? static::VALID_CODE));
        $multiFactorAuthTransfer->setType($type);

        return $multiFactorAuthTransfer;
    }

    /**
     * @param string $expirationDate
     * @param int $status
     * @param string|null $code
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    public function createMultiFactorAuthCodeTransfer(string $expirationDate, int $status, ?string $code = null): MultiFactorAuthCodeTransfer
    {
        return (new MultiFactorAuthCodeTransfer())
            ->setCode($code)
            ->setExpirationDate((new DateTime())->modify($expirationDate)->format('Y-m-d H:i:s'))
            ->setStatus($status);
    }

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function createMultiFactorAuthTypesCollectionTransfer(string $type): MultiFactorAuthTypesCollectionTransfer
    {
        return (new MultiFactorAuthTypesCollectionTransfer())
            ->addMultiFactorAuth((new MultiFactorAuthTransfer())->setType($type));
    }
}
