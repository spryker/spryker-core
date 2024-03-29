<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

interface ProductReviewCollectorToSearchInterface
{
    /**
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function transformPageMapToDocument(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer);
}
