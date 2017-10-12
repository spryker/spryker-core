<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\Zed;

use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;
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
     * @param \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer
     */
    public function getFlattenedLocaleCmsPageData(FlattenedLocaleCmsPageDataRequestTransfer $flattenedLocaleCmsPageDataRequestTransfer)
    {
        return $this->zedRequestClient->call('/cms/gateway/get-flattened-locale-cms-page-data', $flattenedLocaleCmsPageDataRequestTransfer);
    }
}
