<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsContentWidget\Zed;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Spryker\Client\CmsContentWidget\Dependency\Client\CmsContentWidgetToZedRequestInterface;

class CmsContentWidgetStub implements CmsContentWidgetStubInterface
{

    /**
     * @var \Spryker\Client\CmsContentWidget\Dependency\Client\CmsContentWidgetToZedRequestInterface
     */
    protected $cmsContentWidgetToZedRequestBridge;

    /**
     * @param \Spryker\Client\CmsContentWidget\Dependency\Client\CmsContentWidgetToZedRequestInterface $cmsContentWidgetToZedRequestBridge
     */
    public function __construct(CmsContentWidgetToZedRequestInterface $cmsContentWidgetToZedRequestBridge)
    {
        $this->cmsContentWidgetToZedRequestBridge = $cmsContentWidgetToZedRequestBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorData(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer)
    {
        return $this->cmsContentWidgetToZedRequestBridge->call('/cms-content-widget/gateway/expand-cms-page-collector-data', $cmsPageCollectorDataTransfer);
    }

}
