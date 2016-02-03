<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Storage\Provider;

/**
 * Class ReadWriteStorageProvider
 *
 * @method \Spryker\Shared\Storage\Client\ReadWriteInterface getInstance()
 */
abstract class AbstractReadWriteClientProvider extends AbstractKvProvider
{

    protected $clientType = 'ReadWrite';

}
