<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business\ContentWidget;

use Generated\Shared\Transfer\LocaleTransfer;

interface ContentWidgetParameterMapperInterface
{
    /**
     * @param string $content
     *
     * @return array
     */
    public function map($content);

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapByTranslationKey($translationKey, LocaleTransfer $localeTransfer);
}
