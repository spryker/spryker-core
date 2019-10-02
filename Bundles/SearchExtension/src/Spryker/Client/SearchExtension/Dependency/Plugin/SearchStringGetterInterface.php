<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

interface SearchStringGetterInterface
{
    /**
     * Specification:
     * - Gets a string for search.
     *
     * @api
     *
     * @return string|string
     */
    public function getSearchString();
}
