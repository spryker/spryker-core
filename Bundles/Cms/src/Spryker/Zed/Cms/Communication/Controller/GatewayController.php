<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageDataAction(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer): FlattenedLocaleCmsPageDataRequestTransfer
    {
        try {
            $cmsVersionDataTransfer = $this->getFacade()
                ->getCmsVersionData($flattenedLocaleCmsPageDataRequestTransfer->getIdCmsPage());
        } catch (MissingPageException $e) {
            return $flattenedLocaleCmsPageDataRequestTransfer;
        }

        $localeCmsPageDataTransfer = $this->getFacade()
            ->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, $flattenedLocaleCmsPageDataRequestTransfer->getLocale());
        $flattenedLocaleCmsPageData = $this->getFacade()
            ->calculateFlattenedLocaleCmsPageData($localeCmsPageDataTransfer, $flattenedLocaleCmsPageDataRequestTransfer->getLocale());

        return $flattenedLocaleCmsPageDataRequestTransfer->setFlattenedLocaleCmsPageData($flattenedLocaleCmsPageData);
    }
}
