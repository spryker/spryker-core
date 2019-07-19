<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface NamedQueryInterface
{
    /**
     * Specification:
     * - Returns the name of the index to be used for a query.
     *
     * @api
     *
     * @return string
     */
    public function getIndexName(): string;
}
