<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Tabs;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Shared\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ContentTabs extends AbstractTabs
{
    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ContentGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addLocaleTab($tabsViewTransfer);
        $this->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addLocaleTab(TabsViewTransfer $tabsViewTransfer)
    {
        foreach ($this->getAvailableLocales() as $availableLocale) {
            $tabItemTransfer = new TabItemTransfer();
            $tabItemTransfer->setName((string)$availableLocale->getIdLocale());
            $tabItemTransfer->setTemplate('@ContentGui/_partial/locale-tab.twig');
            $tabItemTransfer->setTitle($availableLocale->getLocaleName());
            $tabItemTransfer->setHasError(false);

            $tabsViewTransfer->addTab($tabItemTransfer);
        }

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate('@ContentGui/_template/_form-submit.twig');

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales(): array
    {
        $defaultLocale = new LocaleTransfer();
        $defaultLocale->setLocaleName(ContentGuiConfig::DEFAULT_LOCALE_NAME);

        $locales = $this->localeFacade
            ->getLocaleCollection();

        array_unshift($locales, $defaultLocale);

        return $locales;
    }
}
