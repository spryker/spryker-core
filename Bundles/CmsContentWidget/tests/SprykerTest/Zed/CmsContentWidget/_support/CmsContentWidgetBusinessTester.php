<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidget;

use Codeception\Actor;
use Generated\Shared\Transfer\LocaleTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsContentWidgetBusinessTester extends Actor
{
    use _generated\CmsContentWidgetBusinessTesterActions;

    /**
     * @var string
     */
    protected const LOCALE_US = 'en_US';

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function buildLocaleTransferObject(): LocaleTransfer
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(static::LOCALE_US);
        $localeTransfer->setIsActive(true);

        return $localeTransfer;
    }
}
