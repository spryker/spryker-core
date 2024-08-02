<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleBridge;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

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
class ProductRelationBusinessTester extends Actor
{
    use _generated\ProductRelationBusinessTesterActions;

    /**
     * @var int
     */
    public const ID_TEST_LOCALE = 66;

    /**
     * @return void
     */
    public function mockLocale(): void
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale(static::ID_TEST_LOCALE);
        $productRelationToLocaleBridge = Stub::makeEmpty(
            ProductRelationToLocaleBridge::class,
            [
                'getLocale' => $localeTransfer,
            ],
        );
        $this->setDependency(ProductRelationDependencyProvider::FACADE_LOCALE, $productRelationToLocaleBridge);
    }
}
