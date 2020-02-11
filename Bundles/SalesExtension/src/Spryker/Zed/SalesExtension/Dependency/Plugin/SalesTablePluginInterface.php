<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

interface SalesTablePluginInterface
{
    public const ROW_ACTIONS = 'Actions';

    /**
     * Specifications:
     * - Get item inside foreach in AbstractTable::formatQueryData(). And update it.
     *
     * @api
     *
     * @param callable $buttonGenerator
     * @param array $item
     *
     * @return array
     */
    public function formatTableRow(callable $buttonGenerator, array $item): array;
}
