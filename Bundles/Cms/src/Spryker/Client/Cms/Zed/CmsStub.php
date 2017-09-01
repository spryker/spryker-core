<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\Zed;

use Generated\Shared\Transfer\CmsVersionDataRequestTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CmsStub implements CmsStubInterface
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(CmsVersionDataRequestTransfer $cmsVersionDataRequestTransfer)
    {
        return $this->zedStub->call('/cms/gateway/get-cms-version-data', $cmsVersionDataRequestTransfer);
    }

}
