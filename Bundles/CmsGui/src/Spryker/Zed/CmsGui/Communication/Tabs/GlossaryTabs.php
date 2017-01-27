<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Tabs;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class GlossaryTabs extends AbstractTabs
{

    /**
     * @var CmsGlossaryTransfer $cmsGlossaryTransfer
     */
    protected $cmsGlossaryTransfer;

    /**
     * @param CmsGlossaryTransfer $cmsGlossaryTransfer
     */
    public function __construct(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        $this->cmsGlossaryTransfer = $cmsGlossaryTransfer;
    }
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        return $this->createPlaceHolderTabs($tabsViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function createPlaceHolderTabs(TabsViewTransfer $tabsViewTransfer)
    {
        foreach ($this->cmsGlossaryTransfer->getGlossaryAttributes() as $glossaryAttributesTransfer) {
            $tabItemTransfer = new TabItemTransfer();
            $tabItemTransfer->setName($glossaryAttributesTransfer->getPlaceholder());
            $tabItemTransfer->setTemplate('@CmsGui/_partial/glossary/tab-placeholder.twig');
            $tabItemTransfer->setTitle(ucfirst($glossaryAttributesTransfer->getPlaceholder()));
            $tabsViewTransfer->addTab($tabItemTransfer);
        }

        return $tabsViewTransfer;
    }
}
