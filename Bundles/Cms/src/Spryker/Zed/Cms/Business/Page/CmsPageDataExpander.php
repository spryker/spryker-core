<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\LocaleTransfer;

class CmsPageDataExpander implements CmsPageDataExpanderInterface
{

    /**
     * @var \Spryker\Zed\Cms\Dependency\Plugin\CmsPageDataExpanderPluginInterface[]
     */
    protected $cmsPageDataExpanderPlugins;

    /**
     * @param array $cmsPageDataExpanderPlugins
     */
    public function __construct(array $cmsPageDataExpanderPlugins)
    {
        $this->cmsPageDataExpanderPlugins = $cmsPageDataExpanderPlugins;
    }

    /**
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expand(array $cmsPageData, LocaleTransfer $localeTransfer)
    {
        foreach ($this->cmsPageDataExpanderPlugins as $cmsPageDataExpanderPlugin) {
            $cmsPageData = $cmsPageDataExpanderPlugin->expand($cmsPageData, $localeTransfer);
        }

        return $cmsPageData;
    }

}
