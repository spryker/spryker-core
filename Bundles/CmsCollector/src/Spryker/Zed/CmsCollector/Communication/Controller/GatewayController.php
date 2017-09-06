<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Communication\Controller;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\CmsContentWidget\Communication\Plugin\CmsPageCollector\CmsPageCollectorParameterMapExpanderPlugin;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorDataAction(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer)
    {
        $collectedData = $cmsPageCollectorDataTransfer->getCollectedData();

        foreach ($this->getExpanderPlugins() as $expanderPlugin) {
            $collectedData = $expanderPlugin->expand($collectedData, new LocaleTransfer());
        }
        $cmsPageCollectorDataTransfer->setCollectedData($collectedData);

        return $cmsPageCollectorDataTransfer;
    }

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Plugin\CmsPageCollectorDataExpanderPluginInterface[]
     */
    protected function getExpanderPlugins()
    {
        return [
            new CmsPageCollectorParameterMapExpanderPlugin(),
        ];
    }

}
