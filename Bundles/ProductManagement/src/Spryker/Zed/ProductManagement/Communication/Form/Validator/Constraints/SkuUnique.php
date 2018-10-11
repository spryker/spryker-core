<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Symfony\Component\Validator\Constraint;

class SkuUnique extends Constraint
{
    /**
     * @var string
     */
    public $message = 'SKU should be unique. Another product with sku "{{ sku }}" already exists';

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param array $options
     */
    public function __construct(ProductManagementToProductInterface $productFacade, array $options = [])
    {
        parent::__construct($options);

        $this->productFacade = $productFacade;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    public function getProductFacade(): ProductManagementToProductInterface
    {
        return $this->productFacade;
    }
}
