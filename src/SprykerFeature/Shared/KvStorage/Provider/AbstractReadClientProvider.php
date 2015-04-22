<?php

namespace SprykerFeature\Shared\KvStorage\Provider;

use SprykerFeature\Shared\KvStorage\Client\ReadInterface;

/**
 * Class ReadStorageProvider
 * @package SprykerFeature\Shared\Kv
 * @method ReadInterface getInstance()
 */
abstract class AbstractReadClientProvider extends AbstractKvProvider
{
    protected $clientType = 'Read';
}
