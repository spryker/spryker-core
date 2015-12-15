<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Storage\Provider;

abstract class AbstractReadClientProvider extends AbstractKvProvider
{

    /**
     * @var string
     */
    protected $clientType = 'Read';

}
