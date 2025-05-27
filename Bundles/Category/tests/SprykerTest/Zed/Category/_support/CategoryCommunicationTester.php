<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category;

use Codeception\Actor;
use Generated\Shared\DataBuilder\LocaleBuilder;
use Generated\Shared\Transfer\LocaleTransfer;

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
class CategoryCommunicationTester extends Actor
{
    use _generated\CategoryCommunicationTesterActions;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function buildLocaleTransfer(array $seed = []): LocaleTransfer
    {
        return (new LocaleBuilder($seed))->build();
    }
}
