<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Storage\Provider;

abstract class AbstractReadClientProvider extends AbstractKvProvider
{

    /**
     * @var string
     */
    protected $clientType = 'Read';

}
