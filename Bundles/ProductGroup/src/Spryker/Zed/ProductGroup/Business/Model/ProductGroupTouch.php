<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Shared\ProductGroup\ProductGroupConfig;
use Spryker\Zed\ProductGroup\Dependency\Facade\ProductGroupToTouchInterface;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface;

class ProductGroupTouch implements ProductGroupTouchInterface
{

    /**
     * @var \Spryker\Zed\ProductGroup\Dependency\Facade\ProductGroupToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    protected $productGroupQueryContainer;

    /**
     * @param \Spryker\Zed\ProductGroup\Dependency\Facade\ProductGroupToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface $productGroupQueryContainer
     */
    public function __construct(ProductGroupToTouchInterface $touchFacade, ProductGroupQueryContainerInterface $productGroupQueryContainer)
    {
        $this->touchFacade = $touchFacade;
        $this->productGroupQueryContainer = $productGroupQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return bool
     */
    public function touchProductGroupActive(ProductGroupTransfer $productGroupTransfer)
    {
        $idProductGroup = $productGroupTransfer
            ->requireIdProductGroup()
            ->getIdProductGroup();

        return $this->touchFacade->touchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $idProductGroup);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function touchProductAbstractGroupsActive(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer->requireIdProductAbstracts();

        foreach ($productGroupTransfer->getIdProductAbstracts() as $idProductAbstract) {
            $this->touchFacade->touchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $idProductAbstract);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return bool
     */
    public function touchProductGroupDeleted(ProductGroupTransfer $productGroupTransfer)
    {
        $idProductGroup = $productGroupTransfer
            ->requireIdProductGroup()
            ->getIdProductGroup();

        return $this->touchFacade->touchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP, $idProductGroup);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function touchProductAbstractGroupsDeleted(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer
            ->requireIdProductGroup()
            ->requireIdProductAbstracts();

        foreach ($productGroupTransfer->getIdProductAbstracts() as $idProductAbstract) {
            if ($this->hasProductAbstractOtherGroup($idProductAbstract, $productGroupTransfer->getIdProductGroup())) {
                $this->touchFacade->touchActive(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $idProductAbstract);
            } else {
                $this->touchFacade->touchDeleted(ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS, $idProductAbstract);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductGroup
     *
     * @return bool
     */
    protected function hasProductAbstractOtherGroup($idProductAbstract, $idProductGroup)
    {
        $count = $this->productGroupQueryContainer
            ->queryProductAbstractGroupsByIdProductAbstract($idProductAbstract, $idProductGroup)
            ->count();

        return $count > 0;
    }

}
