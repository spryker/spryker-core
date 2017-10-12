<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface;
use Spryker\Zed\ProductRelation\Communication\Form\ProductRelationFormType;

class ProductRelationTypeDataProvider implements ProductRelationTypeDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct(ProductRelationFacadeInterface $productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => ProductRelationTransfer::class,
            ProductRelationFormType::OPTION_RELATION_CHOICES => $this->buildProductRelationTypeChoiceList(),
        ];
    }

    /**
     * @return array
     */
    protected function buildProductRelationTypeChoiceList()
    {
        $productRelationTypeList = ProductRelationTypes::getAvailableRelationTypes();

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
    public function getData($idProductRelation = null)
    {
        if ($idProductRelation === null) {
            return $this->createInitialProductRelationTransfer();
        }

        $productRelationTransfer = $this->productRelationFacade->findProductRelationById($idProductRelation);
        if ($productRelationTransfer === null) {
            $productRelationTransfer = $this->createInitialProductRelationTransfer();
        }

        return $productRelationTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createInitialProductRelationTransfer()
    {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->setIsActive(false);
        $productRelationTransfer->setQuerySet(new PropelQueryBuilderRuleSetTransfer());
        $productRelationTransfer->setProductRelationType(new ProductRelationTypeTransfer());

        return $productRelationTransfer;
    }
}
