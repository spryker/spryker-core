<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\Resolver;

class ProductRelationDataProviderResolver implements ProductRelationDataProviderResolverInterface
{

    /**
     * @var array|\Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface[]
     */
    protected $productRelationDateProviderList;

    /**
     * @param \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface[] $productRelationDataProvider
     */
    public function __construct(array $productRelationDataProvider)
    {
        $this->productRelationDateProviderList = $productRelationDataProvider;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface|null
     */
    public function resolveByType($type)
    {
        foreach ($this->productRelationDateProviderList as $productRelationDataProvider) {
            if ($productRelationDataProvider->getAcceptedType() === $type) {
                return $productRelationDataProvider;
            }
        }

        return null;
    }

}
