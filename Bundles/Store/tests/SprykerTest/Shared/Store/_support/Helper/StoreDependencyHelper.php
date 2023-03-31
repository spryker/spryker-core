<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\StoreDependencyProvider;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

class StoreDependencyHelper extends Module
{
    use ContainerHelperTrait;
    use DependencyHelperTrait;

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var int
     */
    protected const STORE_ID = 1;

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_US';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $this->getContainerHelper()
            ->getContainer()
            ->set(static::SERVICE_STORE, static::DEFAULT_STORE);
        $this->getContainerHelper()
            ->getContainer()
            ->set(static::SERVICE_LOCALE, static::LOCALE_EN);

        $this->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, [
            $this->createStoreCurrencyLocaleExpandedMock(),
        ]);
    }

    /**
     * @return \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface
     */
    protected function createStoreCurrencyLocaleExpandedMock(): StoreCollectionExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setIdStore(static::STORE_ID)
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY)
            ->setDefaultLocaleIsoCode(static::LOCALE_EN)
            ->setAvailableLocaleIsoCodes([static::LOCALE_EN, static::LOCALE_DE])
            ->setAvailableCurrencyIsoCodes([static::DEFAULT_CURRENCY]);

        return Stub::makeEmpty(StoreCollectionExpanderPluginInterface::class, [
            'expand' => [$storeTransfer],
        ]);
    }
}
