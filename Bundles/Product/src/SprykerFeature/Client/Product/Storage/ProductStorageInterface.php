<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Storage;

interface ProductStorageInterface
{

    /**
     * @param $idAbstractProduct
     *
     * @return mixed
     */
    public function getAbstractProductFromStorageById($idAbstractProduct);

}
