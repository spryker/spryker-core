<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business\Operation;

use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperation;

class OperationManager implements OperationManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(
        ProductSearchQueryContainerInterface $productSearchQueryContainer
    ) {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return bool
     */
    public function hasAttributeOperation($idAttribute, $copyTarget)
    {
        $query = $this->productSearchQueryContainer->queryAttributeOperation($idAttribute, $copyTarget);

        return $query->count() > 0;
    }

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     * @param string $operation
     * @param int $weight
     *
     * @return array
     */
    public function createAttributeOperation($idAttribute, $copyTarget, $operation, $weight)
    {
        $attributeOperationEntity = new SpyProductSearchAttributesOperation();

        $attributeOperationEntity->setTargetField($copyTarget);
        $attributeOperationEntity->setOperation($operation);
        $attributeOperationEntity->setWeighting($weight);
        $attributeOperationEntity->setSourceAttributeId($idAttribute);

        $attributeOperationEntity->save();

        return $attributeOperationEntity->getPrimaryKey();
    }

}
