<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product\Storage;

interface ProductStorageInterface
{

    /**
     * @param $idAbstractProduct
     *
     * @return mixed
     */
    public function getAbstractProductFromStorageById($idAbstractProduct);

}
