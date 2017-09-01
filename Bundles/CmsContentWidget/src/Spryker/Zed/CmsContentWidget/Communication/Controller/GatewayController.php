<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Controller;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
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
        $expandedCollectorData = $this->getFacade()->expandCmsPageCollectorData(
            $cmsPageCollectorDataTransfer->getCollectedData(),
            new LocaleTransfer()
        );
        $cmsPageCollectorDataTransfer->setCollectedData($expandedCollectorData);

        return $cmsPageCollectorDataTransfer;
    }

}
