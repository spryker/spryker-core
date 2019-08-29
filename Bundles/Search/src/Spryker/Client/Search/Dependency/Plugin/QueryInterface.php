<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface as SearchExtensionQueryInterface;

/**
 * @deprecated Use `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface` instead.
 */
interface QueryInterface extends SearchExtensionQueryInterface
{
}

class_alias(SearchExtensionQueryInterface::class, QueryInterface::class, false);
