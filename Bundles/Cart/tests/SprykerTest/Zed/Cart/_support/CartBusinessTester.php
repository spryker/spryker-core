<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface;

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
 * @method \Spryker\Zed\Cart\Business\CartFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CartBusinessTester extends Actor
{
    use _generated\CartBusinessTesterActions;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->getContainer()->set(static::SERVICE_CURRENCY, static::DEFAULT_CURRENCY);
    }

    /**
     * @param callable $checkChangesCallback
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getQuoteChangeObserverPluginMock(callable $checkChangesCallback): QuoteChangeObserverPluginInterface
    {
        return Stub::makeEmpty(QuoteChangeObserverPluginInterface::class, [
            'checkChanges' => $checkChangesCallback,
        ]);
    }
}
