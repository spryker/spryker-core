<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Updater;

use Generated\Shared\Transfer\CmsGlossaryTransfer;

class CmsGlossaryUpdater implements CmsGlossaryUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryAfterFindPluginInterface[]
     */
    protected $cmsGlossaryAfterFindPlugins;

    /**
     * @var \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryBeforeSavePluginInterface[]
     */
    protected $cmsGlossaryBeforeSavePlugins;

    /**
     * @param \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryAfterFindPluginInterface[] $cmsGlossaryAfterFindPlugins
     * @param \Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryBeforeSavePluginInterface[] $cmsGlossaryBeforeSavePlugins
     */
    public function __construct(array $cmsGlossaryAfterFindPlugins, array $cmsGlossaryBeforeSavePlugins)
    {
        $this->cmsGlossaryAfterFindPlugins = $cmsGlossaryAfterFindPlugins;
        $this->cmsGlossaryBeforeSavePlugins = $cmsGlossaryBeforeSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function updateAfterFind(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        foreach ($this->cmsGlossaryAfterFindPlugins as $cmsGlossaryAfterFindPlugin) {
            $cmsGlossaryTransfer = $cmsGlossaryAfterFindPlugin->execute($cmsGlossaryTransfer);
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function updateBeforeSave(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        foreach ($this->cmsGlossaryBeforeSavePlugins as $cmsGlossaryBeforeSavePlugin) {
            $cmsGlossaryTransfer = $cmsGlossaryBeforeSavePlugin->execute($cmsGlossaryTransfer);
        }

        return $cmsGlossaryTransfer;
    }
}
