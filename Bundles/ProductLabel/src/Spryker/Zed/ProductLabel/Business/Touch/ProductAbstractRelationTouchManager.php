<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface;

class ProductAbstractRelationTouchManager implements ProductAbstractRelationTouchManagerInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToProductInterface $productFacade
     */
    public function __construct(ProductLabelToTouchInterface $touchFacade, ProductLabelToProductInterface $productFacade)
    {
        $this->touchFacade = $touchFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchActiveByIdProductAbstract($idProductAbstract)
    {
        $this->touchFacade->touchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $idProductAbstract,
        );

        $this->productFacade->touchProductAbstract($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchDeletedByIdProductAbstract($idProductAbstract)
    {
        $this->touchFacade->touchDeleted(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $idProductAbstract,
        );

        $this->productFacade->touchProductAbstract($idProductAbstract);
    }
}
