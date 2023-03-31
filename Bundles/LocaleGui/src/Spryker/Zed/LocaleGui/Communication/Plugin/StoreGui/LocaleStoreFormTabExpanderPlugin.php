<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class LocaleStoreFormTabExpanderPlugin extends AbstractPlugin implements StoreFormTabExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const LOCALES_TAB_NAME = 'locale_store_relation';

    /**
     * @var string
     */
    protected const LOCALES_TAB_TITLE = 'Locales';

    /**
     * @var string
     */
    protected const LOCALES_TAB_TEMPLATE = '@LocaleGui/_partials/_tabs/locale-store-relation.twig';

    /**
     * {@inheritDoc}
     * - Expands Store form with Locales tab.
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
            ->setName(static::LOCALES_TAB_NAME)
            ->setTitle(static::LOCALES_TAB_TITLE)
            ->setTemplate(static::LOCALES_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
