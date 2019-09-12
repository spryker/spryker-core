<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\UrlStorage\Mapper;

use Generated\Shared\Transfer\UrlRedirectStorageTransfer;

class UrlRedirectStorageMapper implements UrlRedirectStorageMapperInterface
{
    /**
     * @param array $storageData
     * @param \Generated\Shared\Transfer\UrlRedirectStorageTransfer $urlRedirectStorageTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectStorageTransfer
     */
    public function mapStorageDataToUrlRedirectStorageTransfer(
        array $storageData,
        UrlRedirectStorageTransfer $urlRedirectStorageTransfer
    ): UrlRedirectStorageTransfer {
        return (new UrlRedirectStorageTransfer())->fromArray($storageData, true);
    }
}
