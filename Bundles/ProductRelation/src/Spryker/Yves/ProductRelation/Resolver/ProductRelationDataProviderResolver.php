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
    protected $productRelationRendererList;

    /**
     * @param array|\Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface[] $productRelationRendererList
     */
    public function __construct(array $productRelationRendererList)
    {
        $this->productRelationRendererList = $productRelationRendererList;
    }

    /**
     * @param string
     *
     * @return \Spryker\Yves\ProductRelation\DataProvider\ProductRelationDataProviderInterface|null
     */
    public function resolveByType($type)
    {
        foreach ($this->productRelationRendererList as $renderer) {
            if ($renderer->getAcceptedType() === $type) {
                return $renderer;
            }
        }

        return null;
    }

}
