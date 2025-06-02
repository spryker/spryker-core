<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\MultiFactorAuth;

use Codeception\Actor;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;

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
class MultiFactorAuthCommunicationTester extends Actor
{
    use _generated\MultiFactorAuthCommunicationTesterActions;

    /**
     * @var string
     */
    public const VALID_CODE = '123456';

    /**
     * @var string
     */
    public const TYPE_EMAIL = 'email';

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
}
