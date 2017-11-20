<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Provider;

use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

interface MailProviderCollectionAddInterface
{
    /**
     * @param \Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface $mailProvider
     * @param array|string $acceptedMailTypes
     *
     * @return $this
     */
    public function addProvider(MailProviderPluginInterface $mailProvider, $acceptedMailTypes);
}
