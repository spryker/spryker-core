<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface QueryInterface
{
    /**
     * Specification:
     * - Returns a query object.
     *
     * @api
     *
     * @return mixed A query object.
     */
    public function getSearchQuery();
}

class_alias(QueryInterface::class, 'Spryker\\Client\\Search\\Dependency\\Plugin\\QueryInterface', false);
