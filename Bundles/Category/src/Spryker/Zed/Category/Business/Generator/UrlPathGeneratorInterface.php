<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;

interface UrlPathGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function buildCategoryNodeUrlForLocale(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer): string;

    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath): string;
}
