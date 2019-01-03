<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Tabs;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ContentTabs extends AbstractTabs
{
    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface
     */
    protected $localFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface $localFacade
     */
    public function __construct(ContentGuiToLocaleFacadeBridgeInterface $localFacade)
    {
        $this->localFacade = $localFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
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
            $tabItemTransfer->setHasError(true);

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
        $tabsViewTransfer->setFooterTemplate('@ContentGui/_template/_form-submit.twig')
            ->setIsNavigable(true);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales()
    {
        $defaultLocale = new LocaleTransfer();
        $defaultLocale->setLocaleName('Default locale');

        $locales = $this->localFacade
            ->getLocaleCollection();

        array_unshift($locales, $defaultLocale);

        return $locales;
    }
}
