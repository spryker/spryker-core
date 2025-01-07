<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Locale;

use Codeception\Actor;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Yves\Locale\PHPMD)
 */
class LocaleBusinessTester extends Actor
{
    use _generated\LocaleBusinessTesterActions;

    /**
     * @var string
     */
    public const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    public const BC_FEATURE_FLAG_LOCALE_LISTENER = 'BC_FEATURE_FLAG_LOCALE_LISTENER';

    /**
     * @var string
     */
    public const LOCALE = 'en_US';

    /**
     * @var string
     */
    public const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    public const LOCALE_CODE = 'en';

    /**
     * @var string
     */
    public const LOCALE_DE_CODE = 'de';

    /**
     * @var string
     */
    public const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    public const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    public const CURRENCY_EUR = 'EUR';
}
