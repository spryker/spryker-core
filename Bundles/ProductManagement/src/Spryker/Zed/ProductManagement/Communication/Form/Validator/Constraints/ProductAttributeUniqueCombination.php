<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Symfony\Component\Validator\Constraint;

class ProductAttributeUniqueCombination extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The combination {{ combination }} already exists. Please define another one';

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface
     */
    protected $productConcreteSuperAttributeFilterHelper;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFace
     * @param int $idProductAbstract
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface $productConcreteSuperAttributeFilterHelper
     */
    public function __construct(
        ProductManagementToProductInterface $productFace,
        int $idProductAbstract,
        ProductConcreteSuperAttributeFilterHelperInterface $productConcreteSuperAttributeFilterHelper
    ) {
        parent::__construct();

        $this->idProductAbstract = $idProductAbstract;
        $this->productFacade = $productFace;
        $this->productConcreteSuperAttributeFilterHelper = $productConcreteSuperAttributeFilterHelper;
    }

    /**
     * @return int
     */
    public function getIdProductAbstract()
    {
        return $this->idProductAbstract;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    public function getProductFacade()
    {
        return $this->productFacade;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface
     */
    public function getConcreteSuperAttributeFilterHelper(): ProductConcreteSuperAttributeFilterHelperInterface
    {
        return $this->productConcreteSuperAttributeFilterHelper;
    }
}
