<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Symfony\Component\Validator\Constraint;

class UniqueProductPackagingUnitTypeNameConstraint extends Constraint
{
    public const OPTION_FACADE = 'facade';

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ?ProductPackagingUnitTypeTransfer {
        return $this->productPackagingUnitFacade
            ->getProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ?ProductPackagingUnitTypeTransfer {
        return $this->productPackagingUnitFacade
            ->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getMessage($name)
    {
        return sprintf('A product packaging unit type with name "%s" already exists', $name);
    }
}
