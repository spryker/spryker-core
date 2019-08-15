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
     * @param string $termKey
     * @param int|null $contentId
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function getData(string $termKey, ?int $contentId = null): ?ContentTransfer;

    /**
     * @param string $termKey
     * @param \Generated\Shared\Transfer\ContentTransfer|null $contentTransfer
     *
     * @return array
     */
    public function getOptions(string $termKey, ?ContentTransfer $contentTransfer = null): array;
}
