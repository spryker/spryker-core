<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface GroupedResultFormatterPluginInterface
{
    /**
     * Specification:
     * - Returns the group name for the current formatter.
     *
     * @api
     *
     * @return string
     */
    public function getGroupName(): string;
}
