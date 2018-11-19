<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Plugin\CmsPageCollector;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CmsCollector\Dependency\Plugin\CmsPageCollectorDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use CmsPageParameterMapExpanderPlugin instead
 *
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig getConfig()
 */
class CmsPageCollectorParameterMapExpanderPlugin extends AbstractPlugin implements CmsPageCollectorDataExpanderPluginInterface
{
    /**
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expand(array $collectedData, LocaleTransfer $localeTransfer)
    {
        return $this->getFacade()
            ->expandCmsPageCollectorData($collectedData, $localeTransfer);
    }
}
