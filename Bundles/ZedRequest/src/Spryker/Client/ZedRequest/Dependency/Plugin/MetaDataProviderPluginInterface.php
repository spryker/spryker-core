<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MetaDataProviderPluginInterface
{

    /**
     * Specification:
     *  - Provide additional data to zed request which will be send with which yves to zed.
     *  - The meta data is part of \Spryker\Shared\ZedRequest\Client\AbstractRequest request object
     *  - The first parameter is request transfer as provided by high level client code (cart, calculation, sales)
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getRequestMetaData(TransferInterface $transfer);

}
