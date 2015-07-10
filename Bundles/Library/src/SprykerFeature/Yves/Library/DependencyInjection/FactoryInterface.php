<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\DependencyInjection;

use Generated\Yves\Factory;

interface FactoryInterface
{

    /**
     * @param Factory $factory
     */
    public function setFactory(Factory $factory);

}
