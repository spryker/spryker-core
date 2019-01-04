<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ContentTransfer;

interface ContentFormDataProviderInterface
{
    /**
     * @param int|null $contentId
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function getData(?int $contentId = null): ContentTransfer;

    /**
     * @return array
     */
    public function getOptions(): array;
}
