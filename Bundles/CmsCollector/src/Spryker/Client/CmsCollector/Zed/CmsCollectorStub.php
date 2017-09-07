<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsCollector\Zed;

use Generated\Shared\Transfer\CmsPageCollectorDataTransfer;
use Spryker\Client\CmsCollector\Dependency\Client\CmsCollectorToZedRequestInterface;

class CmsCollectorStub implements CmsCollectorStubInterface
{

    /**
     * @var \Spryker\Client\CmsCollector\Dependency\Client\CmsCollectorToZedRequestInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CmsCollector\Dependency\Client\CmsCollectorToZedRequestInterface $zedRequestClient
     */
    public function __construct(CmsCollectorToZedRequestInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageCollectorDataTransfer
     */
    public function expandCmsPageCollectorData(CmsPageCollectorDataTransfer $cmsPageCollectorDataTransfer)
    {
        return $this->zedRequestClient->call('/cms-collector/gateway/expand-cms-page-collector-data', $cmsPageCollectorDataTransfer);
    }

}
