<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

use Spryker\Shared\ProductLabel\ProductLabelConfig;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface;

class AbstractProductRelationTouchManager implements AbstractProductRelationTouchManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface $touchFacade
     */
    public function __construct(ProductLabelToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    public function touchActiveForAbstractProduct($idAbstractProduct)
    {
        $this->touchFacade->touchActive(
            ProductLabelConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $idAbstractProduct
        );
    }

}
