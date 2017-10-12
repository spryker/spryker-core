<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Dependency\Renderer;

use Generated\Shared\Transfer\LocaleTransfer;

interface MailToRendererInterface
{
    /**
     * @param string $template
     * @param array $options
     *
     * @return string
     */
    public function render($template, array $options);

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function setLocaleTransfer(LocaleTransfer $localeTransfer);
}
