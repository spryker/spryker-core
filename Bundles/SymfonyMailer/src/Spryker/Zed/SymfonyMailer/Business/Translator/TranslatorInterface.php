<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Business\Translator;

use Generated\Shared\Transfer\MailTransfer;

interface TranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $keyName
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function translate(MailTransfer $mailTransfer, string $keyName, array $data = []): string;
}
