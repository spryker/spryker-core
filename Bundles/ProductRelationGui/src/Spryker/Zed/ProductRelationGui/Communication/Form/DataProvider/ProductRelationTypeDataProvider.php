<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductRelationGui\Communication\Form\ProductRelationFormType;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface;

class ProductRelationTypeDataProvider
{
    public const TYPE_RELATED_PRODUCTS = 'related-products';
    public const TYPE_UP_SELLING = 'up-selling';

    public const OPTION_PRODUCT_RELATION_KEY_DISABLED = 'option_product_relation_key_disabled';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct(ProductRelationGuiToProductRelationFacadeInterface $productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @param bool $isProductRelationKeyDisabled
     *
     * @return array
     */
    public function getOptions(bool $isProductRelationKeyDisabled = false): array
    {
        return [
            'data_class' => ProductRelationTransfer::class,
            ProductRelationFormType::OPTION_RELATION_CHOICES => $this->buildProductRelationTypeChoiceList(),
            static::OPTION_PRODUCT_RELATION_KEY_DISABLED => $isProductRelationKeyDisabled,
        ];
    }

    /**
     * @return array
     */
    protected function buildProductRelationTypeChoiceList(): array
    {
        $productRelationTypeList = [
            static::TYPE_RELATED_PRODUCTS,
            static::TYPE_UP_SELLING,
        ];

        $productRelationChoiceTypeList = [];
        foreach ($productRelationTypeList as $type) {
            $productRelationChoiceTypeList[$type] = $type;
        }

        return $productRelationChoiceTypeList;
    }

    /**
     * @param int|null $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function getData(?int $idProductRelation = null): ProductRelationTransfer
    {
        if ($idProductRelation === null) {
            return $this->createProductRelationTransfer();
        }

        $productRelationResponseTransfer = $this->productRelationFacade->findProductRelationById($idProductRelation);
        $productRelationTransfer = $productRelationResponseTransfer->getProductRelation();

        if (!$productRelationTransfer) {
            $productRelationTransfer = $this->createProductRelationTransfer();
        }

        return $productRelationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationTransfer(): ProductRelationTransfer
    {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->setIsActive(false);
        $productRelationTransfer->setQuerySet(new PropelQueryBuilderRuleSetTransfer());
        $productRelationTransfer->setProductRelationType(new ProductRelationTypeTransfer());
        $productRelationTransfer->setStoreRelation(new StoreRelationTransfer());

        return $productRelationTransfer;
    }
}
