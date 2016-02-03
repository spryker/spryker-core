<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product\Storage;

interface ProductStorageInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return mixed
     */
    public function getProductAbstractFromStorageById($idProductAbstract);

}
