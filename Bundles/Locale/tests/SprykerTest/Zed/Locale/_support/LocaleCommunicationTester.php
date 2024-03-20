<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\Locale;

use Codeception\Actor;

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
 * @SuppressWarnings(\SprykerTest\Zed\Locale\PHPMD)
 */
class LocaleCommunicationTester extends Actor
{
    use _generated\LocaleCommunicationTesterActions;

    /**
     * @var string
     */
    public const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    public const LOCALE = 'de_DE';

    /**
     * @var string
     */
    public const BC_FEATURE_FLAG_LOCALE_LISTENER = 'BC_FEATURE_FLAG_LOCALE_LISTENER';
}
