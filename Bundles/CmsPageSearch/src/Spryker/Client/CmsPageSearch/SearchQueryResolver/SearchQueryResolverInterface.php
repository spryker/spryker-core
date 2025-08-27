<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Client\CmsPageSearch\SearchQueryResolver;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchQueryResolverInterface
{
    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function resolve(): QueryInterface;
}
