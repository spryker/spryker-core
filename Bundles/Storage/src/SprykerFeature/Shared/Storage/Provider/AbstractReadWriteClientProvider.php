<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Storage\Provider;

use Spryker\Shared\Storage\Client\ReadWriteInterface;

/**
 * Class ReadWriteStorageProvider
 *
 * @method ReadWriteInterface getInstance()
 */
abstract class AbstractReadWriteClientProvider extends AbstractKvProvider
{

    protected $clientType = 'ReadWrite';

}
