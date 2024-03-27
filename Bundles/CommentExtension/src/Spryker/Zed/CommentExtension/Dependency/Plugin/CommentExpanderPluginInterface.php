<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentExtension\Dependency\Plugin;

/**
 * Implement this plugin to provide additional data for comments once they are retrieved from the persistence.
 */
interface CommentExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands comment transfers with additional data.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function expand(array $commentTransfers): array;
}
