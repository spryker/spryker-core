<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\Zed;

use Generated\Shared\Transfer\CmsVersionDataRequestTransfer;
use Spryker\Client\Cms\Dependency\Client\CmsToZedRequestInterface;

class CmsStub implements CmsStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $cmsToZedRequestBridge;

    /**
     * @param \Spryker\Client\Cms\Dependency\Client\CmsToZedRequestInterface $cmsToZedRequestBridge
     */
    public function __construct(CmsToZedRequestInterface $cmsToZedRequestBridge)
    {
        $this->cmsToZedRequestBridge = $cmsToZedRequestBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer)
    {
        return $this->cmsToZedRequestBridge->call('/cms/gateway/get-cms-version-data', $cmsVersionDataRequestTransfer);
    }

}
