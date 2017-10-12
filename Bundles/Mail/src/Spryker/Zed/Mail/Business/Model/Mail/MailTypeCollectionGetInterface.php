<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail;

interface MailTypeCollectionGetInterface
{
    /**
     * @param string $mailType
     *
     * @throws \Spryker\Zed\Mail\Business\Exception\MailNotFoundException
     *
     * @return \Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface
     */
    public function get($mailType);

    /**
     * @param string $mailType
     *
     * @return bool
     */
    public function has($mailType);
}
