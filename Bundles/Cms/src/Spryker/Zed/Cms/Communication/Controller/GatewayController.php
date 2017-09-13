<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer;
use Generated\Shared\Transfer\CmsVersionDataRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionDataAction(CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer)
    {
        return $this->getFacade()->getCmsVersionData($cmsVersionDataRequestTransfer->getIdCmsPage());
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer
     */
    public function expandCmsPageDataAction(CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer)
    {
        $cmsPageDataExpandRequestTransfer->setCmsPageData(
            $this->getFacade()->expandCmsPageData(
                $cmsPageDataExpandRequestTransfer->getCmsPageData(),
                $cmsPageDataExpandRequestTransfer->getLocale()
            )
        );

        return $cmsPageDataExpandRequestTransfer;
    }

}
