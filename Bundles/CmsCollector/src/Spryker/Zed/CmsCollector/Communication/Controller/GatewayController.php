<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Communication\Controller;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CmsCollector\Communication\CmsCollectorCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorDataAction(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer)
    {
        $localeTransfer = new LocaleTransfer();
        $collectedData = $cmsPageCollectorDataTransfer->getCollectedData();

        foreach ($this->getFactory()->getCollectorDataExpanderPlugins() as $collectorDataExpanderPlugin) {
            $collectedData = $collectorDataExpanderPlugin->expand($collectedData, $localeTransfer);
        }
        $cmsPageCollectorDataTransfer->setCollectedData($collectedData);

        return $cmsPageCollectorDataTransfer;
    }

}
