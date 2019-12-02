<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin;

interface ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface
{
    /**
     * Specification:
     * - Provides subtabs for Configurable Bundle Template Slot edit page.
     *
     * @api
     *
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface[]
     */
    public function provideSubTabs(): array;
}
