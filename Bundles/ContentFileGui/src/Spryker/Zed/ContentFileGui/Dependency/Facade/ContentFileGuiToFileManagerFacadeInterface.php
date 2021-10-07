<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Dependency\Facade;

interface ContentFileGuiToFileManagerFacadeInterface
{
    /**
     * @param array<int> $idFiles
     *
     * @return array<\Generated\Shared\Transfer\FileManagerDataTransfer>
     */
    public function getFilesByIds(array $idFiles): array;
}
