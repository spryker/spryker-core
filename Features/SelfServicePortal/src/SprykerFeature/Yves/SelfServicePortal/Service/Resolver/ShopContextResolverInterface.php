<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Resolver;

use Generated\Shared\Transfer\ShopContextTransfer;

interface ShopContextResolverInterface
{
    public function resolve(): ShopContextTransfer;
}
