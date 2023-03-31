<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CountryGui\Communication\CountryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class CountryStoreFormTabExpanderPlugin extends AbstractPlugin implements StoreFormTabExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const COUNTRIES_TAB_NAME = 'country_store_relation';

    /**
     * @var string
     */
    protected const COUNTRIES_TAB_TITLE = 'Delivery regions';

    /**
     * @var string
     */
    protected const COUNTRIES_TAB_TEMPLATE = '@CountryGui/_partials/_tabs/country-store-relation.twig';

    /**
     * {@inheritDoc}
     * - Expands store form with countries tab.
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
            ->setName(static::COUNTRIES_TAB_NAME)
            ->setTitle(static::COUNTRIES_TAB_TITLE)
            ->setTemplate(static::COUNTRIES_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
