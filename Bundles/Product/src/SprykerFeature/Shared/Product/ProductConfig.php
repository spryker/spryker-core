<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Product;

use SprykerFeature\Shared\Library\ConfigInterface;

interface ProductConfig extends ConfigInterface
{

    const RESOURCE_TYPE_PRODUCT = 'product';
    const RESOURCE_TYPE_ABSTRACT_PRODUCT = 'abstract_product';

}
