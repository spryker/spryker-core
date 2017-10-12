<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail;

use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

interface MailTypeCollectionAddInterface
{
    /**
     * @param \Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface $mailType
     *
     * @return mixed
     */
    public function add(MailTypePluginInterface $mailType);
}
