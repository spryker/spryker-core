<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Dependency\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;

interface SymfonyMailerToRendererInterface
{
    /**
     * @param string $template
     * @param array<string, mixed> $context
     *
     * @return string
     */
    public function render(string $template, array $context = []): string;

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function setLocaleTransfer(LocaleTransfer $localeTransfer): void;
}
