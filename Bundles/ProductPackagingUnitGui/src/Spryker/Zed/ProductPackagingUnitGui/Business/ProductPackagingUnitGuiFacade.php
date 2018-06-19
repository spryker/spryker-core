<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductPackagingUnitGui\Business\ProductPackagingUnitGuiBusinessFactory getFactory()
 */
class ProductPackagingUnitGuiFacade extends AbstractFacade implements ProductPackagingUnitGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getInfrastructuralPackagingUnitTypeKeys(): array
    {
        return $this->getFactory()
            ->getProductPackagingUnitFacade()
            ->getInfrastructuralPackagingUnitTypeKeys();
    }
}
