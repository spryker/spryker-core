<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Symfony\Component\Validator\Constraint;

class ProductAttributeUniqueCombination extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Same attribute values combination already exists';

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacace
     * @param int $idProductAbstract
     * @param array $options
     */
    public function __construct(ProductManagementToProductInterface $productFacace, int $idProductAbstract, array $options = [])
    {
        parent::__construct($options);

        $this->idProductAbstract = $idProductAbstract;
        $this->productFacade = $productFacace;
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
}
