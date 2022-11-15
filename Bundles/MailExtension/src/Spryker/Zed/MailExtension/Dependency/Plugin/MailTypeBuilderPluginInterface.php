<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MailExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MailTransfer;

/**
 * Use this plugin for preparing data for mail sending.
 */
interface MailTypeBuilderPluginInterface
{
    /**
     * Specification:
     * - Returns the name of the mail.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Specification:
     * - Builds the `MailTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer;
}
