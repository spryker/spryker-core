<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequestExtension\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * Use this plugin if you need to provide additional data to Zed request.
 */
interface MetaDataProviderPluginInterface
{
    /**
     * Specification:
     *  - Provides additional data to Zed request which will be sent with from Yves to Zed.
     *  - The meta data is part of Spryker\Shared\ZedRequest\Client\AbstractRequest request object.
     *  - The first parameter is request transfer as provided by high level client code (e.g. cart, calculation, sales).
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getRequestMetaData(TransferInterface $transfer): TransferInterface;
}
