<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Service\Storage;

interface ProductStorageInterface
{

    /**
     * @param $idAbstractProduct
     * @return mixed
     */
    public function getAbstractProductFromStorageById($idAbstractProduct);

}
