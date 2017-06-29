<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

interface ProductAttributeGuiFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributes($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getMetaAttributesForProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function getProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract);

    /**
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestKeys($searchText = '', $limit = 10);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param array $attributes
     *
     * @return void
     */
    public function saveAbstractAttributes($idProductAbstract, array $attributes);

}
