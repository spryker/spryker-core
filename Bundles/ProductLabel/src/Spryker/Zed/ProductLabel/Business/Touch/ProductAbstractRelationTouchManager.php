<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToTouchInterface;

class ProductAbstractRelationTouchManager implements ProductAbstractRelationTouchManagerInterface
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
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchActiveByIdProductAbstract($idProductAbstract)
    {
        $this->touchFacade->touchActive(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $idProductAbstract
        );
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
            $idProductAbstract
        );
    }

}
