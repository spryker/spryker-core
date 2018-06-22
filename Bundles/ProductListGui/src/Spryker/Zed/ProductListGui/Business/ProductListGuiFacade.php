<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductListGui\Business\ProductListGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface getRepository()
 */
class ProductListGuiFacade extends AbstractFacade implements ProductListGuiFacadeInterface
{
    /**
     * @api
     *
     * @module Category
     *
     * @return string[] [<category id> => <category name in english locale>]
     */
    public function getAllCategoryNames(): array
    {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->getRepository()
            ->getAllCategoryNames($localeTransfer);
    }

    /**
     * @api
     *
     * @module Product
     *
     * @return string[] [<product id> => <product name in english locale>]
     */
    public function getAllProductNames(): array
    {
        $localeTransfer = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        return $this->getRepository()
            ->getAllProductNames($localeTransfer);
    }
}
