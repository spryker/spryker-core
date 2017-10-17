<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\Resolver;

interface ProductRelationDataProviderResolverInterface
{
    /**
     * @param string $type
     *
     * @return \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface|null
     */
    public function resolveByType($type);
}
