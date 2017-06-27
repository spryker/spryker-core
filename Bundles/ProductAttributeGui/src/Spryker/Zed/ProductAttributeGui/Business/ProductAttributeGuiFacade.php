<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiFacade extends AbstractFacade implements ProductAttributeGuiFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributes($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getAttributes($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributes($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributes($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getProductAbstractAttributeValues($idProductAbstract);
    }

    /**
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestProductAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->suggestKeys($searchText, $limit);
    }

    public function updateProductAbstractAttributes($idProductAbstract, array $data)
    {
        $this->getFactory()
            ->createProductAttributeManager()
            ->updateProductAbstractAttributes($idProductAbstract, $data);
    }

}
