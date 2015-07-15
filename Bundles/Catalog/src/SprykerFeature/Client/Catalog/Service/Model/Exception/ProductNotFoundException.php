<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model\Exception;

class ProductNotFoundException extends \RuntimeException
{

    public function __construct($id)
    {
        parent::__construct('The product was not found' . PHP_EOL . '[id] ' . $id);
    }

}
