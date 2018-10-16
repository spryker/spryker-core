<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Plugin;

use Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 */
class RemoveProductCategoryRelationPlugin extends AbstractPlugin implements CategoryRelationDeletePluginInterface
{
    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this
            ->getFacade()
            ->removeAllProductMappingsForCategory($idCategory);
    }
}
