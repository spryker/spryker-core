<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Touch;

use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Shared\ProductSet\ProductSetConfig;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToTouchInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetTouch implements ProductSetTouchInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     */
    public function __construct(ProductSetToTouchInterface $touchFacade, ProductSetQueryContainerInterface $productSetQueryContainer)
    {
        $this->touchFacade = $touchFacade;
        $this->productSetQueryContainer = $productSetQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetActive(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForTouch($productSetTransfer);

        return $this->touchFacade->touchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetDeleted(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForTouch($productSetTransfer);

        return $this->touchFacade->touchDeleted(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return bool
     */
    public function touchProductSetByStatus(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForTouchByStatus($productSetTransfer);

        if ($productSetTransfer->getIsActive()) {
            return $this->touchProductSetActive($productSetTransfer);
        }

        return $this->touchProductSetDeleted($productSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForTouch(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForTouchByStatus(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer
            ->requireIdProductSet()
            ->requireIsActive();
    }
}
