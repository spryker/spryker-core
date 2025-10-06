<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Generated\Shared\Transfer\UrlCollectionTransfer;
use Generated\Shared\Transfer\UrlCriteriaTransfer;
use Generated\Shared\Transfer\UrlTransfer;

interface UrlRepositoryInterface
{
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer;

    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer, bool $ignoreRedirects): bool;

    public function getUrlCollection(UrlCriteriaTransfer $urlCriteriaTransfer): UrlCollectionTransfer;
}
