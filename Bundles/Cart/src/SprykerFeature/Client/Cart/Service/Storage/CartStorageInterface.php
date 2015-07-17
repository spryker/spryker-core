<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service\Storage;

interface CartStorageInterface
{

    /**
     * @param int $idProduct
     *
     * @return mixed
     */
    public function getProduct($idProduct);

}
