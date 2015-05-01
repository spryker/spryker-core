<?php

namespace SprykerFeature\Shared\KvStorage\Provider;

use SprykerFeature\Shared\KvStorage\Client\ReadWriteInterface;

/**
 * Class ReadWriteStorageProvider
 * @package SprykerFeature\Shared\Kv
 * @method ReadWriteInterface getInstance()
 */
abstract class AbstractReadWriteClientProvider extends AbstractKvProvider
{
    protected $clientType = 'ReadWrite';
}
