<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;

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
class ProductOptionCartConnectorBusinessTester extends Actor
{
    use _generated\ProductOptionCartConnectorBusinessTesterActions;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

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

        $this->setDependency(CurrencyDependencyProvider::FACADE_STORE, $this->createCurrencyToStoreFacadeMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected function createCurrencyToStoreFacadeMock(): CurrencyToStoreFacadeInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        return Stub::makeEmpty(CurrencyToStoreFacadeInterface::class, [
            'getCurrentStore' => $storeTransfer,
        ]);
    }
}
