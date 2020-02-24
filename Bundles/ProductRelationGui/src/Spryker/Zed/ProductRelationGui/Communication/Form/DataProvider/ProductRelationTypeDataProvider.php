<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\ProductRelationGui\Communication\Form\ProductRelationFormType;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductRelationFacadeInterface;

class ProductRelationTypeDataProvider
{
    public const TYPE_RELATED_PRODUCTS = 'related-products';
    public const TYPE_UP_SELLING = 'up-selling';

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
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ProductRelationTransfer::class,
            ProductRelationFormType::OPTION_RELATION_CHOICES => $this->buildProductRelationTypeChoiceList(),
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

        $productRelationTransfer = $this->productRelationFacade->findProductRelationById($idProductRelation);
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

        return $productRelationTransfer;
    }
}
