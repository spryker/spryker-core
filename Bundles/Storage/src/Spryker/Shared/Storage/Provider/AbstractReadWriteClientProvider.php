<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
