<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Updater;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;

class CmsBlockGlossaryUpdater implements CmsBlockGlossaryUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryAfterFindPluginInterface[]
     */
    protected $cmsBlockGlossaryAfterFindPlugins;

    /**
     * @var \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryBeforeSavePluginInterface[]
     */
    protected $cmsBlockGlossaryBeforeSavePlugins;

    /**
     * @param \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryAfterFindPluginInterface[] $cmsBlockGlossaryAfterFindPlugins
     * @param \Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryBeforeSavePluginInterface[] $cmsBlockGlossaryBeforeSavePlugins
     */
    public function __construct(array $cmsBlockGlossaryAfterFindPlugins, array $cmsBlockGlossaryBeforeSavePlugins)
    {
        $this->cmsBlockGlossaryAfterFindPlugins = $cmsBlockGlossaryAfterFindPlugins;
        $this->cmsBlockGlossaryBeforeSavePlugins = $cmsBlockGlossaryBeforeSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function updateAfterFind(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        foreach ($this->cmsBlockGlossaryAfterFindPlugins as $cmsBlockGlossaryAfterFindPlugin) {
            $cmsBlockGlossaryTransfer = $cmsBlockGlossaryAfterFindPlugin->execute($cmsBlockGlossaryTransfer);
        }

        return $cmsBlockGlossaryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function updateBeforeSave(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        foreach ($this->cmsBlockGlossaryBeforeSavePlugins as $cmsBlockGlossaryBeforeSavePlugin) {
            $cmsBlockGlossaryTransfer = $cmsBlockGlossaryBeforeSavePlugin->execute($cmsBlockGlossaryTransfer);
        }

        return $cmsBlockGlossaryTransfer;
    }
}
