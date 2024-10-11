<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGuiExtension\Dependency\Plugin;

/**
 * Provides an ability to expand button list for the current item.
 *
 * Implement this plugin interface to expand `FileManagerGui` view table after the fetching file data from the persistence.
 */
interface FileInfoViewTableActionsExpanderPluginInterface
{
    /**
     * Specification:
     * - Modifies FileManager view table actions list.
     *
     * @api
     *
     * @param array<mixed> $item
     * @param array<\Generated\Shared\Transfer\ButtonTransfer> $buttonTransferCollection
     *
     * @return array<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function execute(array $item, array $buttonTransferCollection): array;
}
