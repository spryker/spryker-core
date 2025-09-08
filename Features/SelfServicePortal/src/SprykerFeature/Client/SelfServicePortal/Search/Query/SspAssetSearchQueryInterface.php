<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Search\Query;

use Elastica\Query;

interface SspAssetSearchQueryInterface
{
    public function createQuery(?string $searchString): Query;
}
