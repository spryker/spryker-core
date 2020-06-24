<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MailTransfer;

/**
 * Allows to expand a mail transfer with a data that can be used in the email templates.
 */
interface AuthMailExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands an auth mail transfer with an additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function expand(MailTransfer $mailTransfer): MailTransfer;
}
