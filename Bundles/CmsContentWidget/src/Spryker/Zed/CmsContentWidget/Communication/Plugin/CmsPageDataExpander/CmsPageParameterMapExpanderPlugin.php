<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Plugin\CmsPageDataExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Cms\Dependency\Plugin\CmsPageDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig getConfig()
 */
class CmsPageParameterMapExpanderPlugin extends AbstractPlugin implements CmsPageDataExpanderPluginInterface
{
    /**
     * @api
     *
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expand(array $cmsPageData, LocaleTransfer $localeTransfer)
    {
        return $this->getFacade()
            ->expandCmsPageCollectorData($cmsPageData, $localeTransfer);
    }
}
