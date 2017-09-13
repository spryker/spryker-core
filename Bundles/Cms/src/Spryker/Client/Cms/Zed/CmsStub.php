<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\Zed;

use Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer;
use Generated\Shared\Transfer\CmsVersionDataRequestTransfer;
use Spryker\Client\Cms\Dependency\Client\CmsToZedRequestInterface;

class CmsStub implements CmsStubInterface
{

    /**
     * @var \Spryker\Client\Cms\Dependency\Client\CmsToZedRequestInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Cms\Dependency\Client\CmsToZedRequestInterface $zedRequestClient
     */
    public function __construct(CmsToZedRequestInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer)
    {
        return $this->zedRequestClient->call('/cms/gateway/get-cms-version-data', $cmsVersionDataRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageDataExpandRequestTransfer
     */
    public function expandCmsPageData(CmsPageDataExpandRequestTransfer $cmsPageDataExpandRequestTransfer)
    {
        return $this->zedRequestClient->call('/cms/gateway/expand-cms-page-data', $cmsPageDataExpandRequestTransfer);
    }

}
