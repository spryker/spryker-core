<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Dependency\Plugin;

use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;

interface MailTypePluginInterface
{
    /**
     * Specification:
     * - Returns the name of the MailType
     *
     * @api
     *
     * @return string
     */
    public function getName();

    /**
     * Specification:
     * - Builds the MailTransfer
     *
     * @api
     *
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return void
     */
    public function build(MailBuilderInterface $mailBuilder);
}
