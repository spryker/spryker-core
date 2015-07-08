<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Storage\Provider;

use SprykerFeature\Shared\Storage\Client\ReadWriteInterface;

/**
 * Class ReadWriteStorageProvider
 * @package SprykerFeature\Shared\Kv
 * @method ReadWriteInterface getInstance()
 */
abstract class AbstractReadWriteClientProvider extends AbstractKvProvider
{
    protected $clientType = 'ReadWrite';
}
