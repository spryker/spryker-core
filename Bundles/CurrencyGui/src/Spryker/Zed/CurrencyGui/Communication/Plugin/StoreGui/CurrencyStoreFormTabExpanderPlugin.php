<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CurrencyGui\Communication\CurrencyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CurrencyGui\CurrencyGuiConfig getConfig()
 */
class CurrencyStoreFormTabExpanderPlugin extends AbstractPlugin implements StoreFormTabExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const CURRENCIES_TAB_NAME = 'locale_currency_relation';

    /**
     * @var string
     */
    protected const CURRENCIES_TAB_TITLE = 'Currencies';

    /**
     * @var string
     */
    protected const CURRENCIES_TAB_TEMPLATE = '@CurrencyGui/_partials/_tabs/currency-store-relation.twig';

    /**
     * {@inheritDoc}
     * - Expands Store form with Currencies tab.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(static::CURRENCIES_TAB_NAME)
            ->setTitle(static::CURRENCIES_TAB_TITLE)
            ->setTemplate(static::CURRENCIES_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
