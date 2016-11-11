<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail;

use Spryker\Zed\Mail\Dependency\Plugin\MailTypeInterface;

interface MailTypeCollectionAddInterface
{

    /**
     * @param \Spryker\Zed\Mail\Dependency\Plugin\MailTypeInterface $mailType
     *
     * @return mixed
     */
    public function add(MailTypeInterface $mailType);

}
