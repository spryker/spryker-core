<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDynamicEntityConnector\Business\Updater;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractRelationsTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Spryker\Zed\ProductDynamicEntityConnector\Dependency\Facade\ProductDynamicEntityConnectorToProductFacadeInterface;

class UrlUpdater implements UrlUpdaterInterface
{
    /**
     * @var string
     */
    protected const TABLE_NAME = SpyProductAbstractLocalizedAttributesTableMap::TABLE_NAME;

    /**
     * @var string
     */
    protected const FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    protected const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var \Spryker\Zed\ProductDynamicEntityConnector\Dependency\Facade\ProductDynamicEntityConnectorToProductFacadeInterface
     */
    protected ProductDynamicEntityConnectorToProductFacadeInterface $productFacade;

    /**
     * @param \Spryker\Zed\ProductDynamicEntityConnector\Dependency\Facade\ProductDynamicEntityConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(ProductDynamicEntityConnectorToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateProductAbstractUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        if ($dynamicEntityPostEditRequestTransfer->getTableNameOrFail() !== static::TABLE_NAME) {
            return new DynamicEntityPostEditResponseTransfer();
        }

        $productAbstractCriteriaTransfer = $this->getProductAbstractCriteriaTransfer($dynamicEntityPostEditRequestTransfer);
        $productAbstractCollectionTransfer = $this->productFacade->getProductAbstractCollection($productAbstractCriteriaTransfer);
        $productAbstracts = $this->getProductAbstractsIndexedById($productAbstractCollectionTransfer);

        $this->updateProductUrls($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities()->getArrayCopy(), $productAbstracts);

        return new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param array<\Generated\Shared\Transfer\RawDynamicEntityTransfer> $rawDynamicEntities
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstracts
     *
     * @return void
     */
    protected function updateProductUrls(array $rawDynamicEntities, array $productAbstracts): void
    {
        $productAbstractTransfers = [];
        foreach ($rawDynamicEntities as $rawDynamicEntity) {
            $productAbstractTransfers[$rawDynamicEntity->getFields()[static::FK_PRODUCT_ABSTRACT]] = $productAbstracts[$rawDynamicEntity->getFields()[static::FK_PRODUCT_ABSTRACT]];
        }
        $this->productFacade->updateProductsUrl($productAbstractTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    protected function getProductAbstractsIndexedById(ProductAbstractCollectionTransfer $productAbstractCollectionTransfer): array
    {
        $productAbstracts = [];

        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstracts[$productAbstractTransfer->getIdProductAbstract()] = $productAbstractTransfer;
        }

        return $productAbstracts;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer
     */
    protected function getProductAbstractCriteriaTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): ProductAbstractCriteriaTransfer {
        $ids = [];
        foreach ($dynamicEntityPostEditRequestTransfer->getRawDynamicEntities() as $rawDynamicEntity) {
            $ids[] = $rawDynamicEntity->getFields()[static::FK_PRODUCT_ABSTRACT];
        }

        return (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractConditions(
                (new ProductAbstractConditionsTransfer())
                    ->setIds($ids),
            )
            ->setProductAbstractRelations(
                (new ProductAbstractRelationsTransfer())
                    ->setWithLocalizedAttributes(true),
            );
    }
}
