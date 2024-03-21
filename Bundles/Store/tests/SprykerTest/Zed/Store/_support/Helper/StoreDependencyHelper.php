<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Country\Communication\Plugin\Store\CountryStoreCollectionExpanderPlugin;
use Spryker\Zed\Currency\Communication\Plugin\Store\CurrencyStoreCollectionExpanderPlugin;
use Spryker\Zed\Locale\Communication\Plugin\Store\LocaleStoreCollectionExpanderPlugin;
use Spryker\Zed\Store\StoreDependencyProvider;
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
            ->set(static::SERVICE_LOCALE, static::LOCALE_EN);

        $this->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, [
            new CurrencyStoreCollectionExpanderPlugin(),
            new CountryStoreCollectionExpanderPlugin(),
            new LocaleStoreCollectionExpanderPlugin(),
        ]);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        parent::_after($test);
        if ($this->getContainerHelper()->getContainer()->has(static::SERVICE_LOCALE)) {
            $this->getContainerHelper()->getContainer()->remove(static::SERVICE_LOCALE);
        }
        $this->removeCurrentStore();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function addCurrentStore(StoreTransfer $storeTransfer): void
    {
        $this->getContainerHelper()
            ->getContainer()
            ->set(static::SERVICE_STORE, $storeTransfer->getName());
    }

    /**
     * @return void
     */
    public function removeCurrentStore(): void
    {
        if ($this->getContainerHelper()->getContainer()->has(static::SERVICE_STORE)) {
            $this->getContainerHelper()->getContainer()->remove(static::SERVICE_STORE);
        }
    }
}
