<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Provider;

interface MailProviderCollectionGetInterface
{
    /**
     * @param string $mailType
     *
     * @return \Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface[]
     */
    public function getProviderForMailType($mailType);
}
