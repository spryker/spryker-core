<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsBlockToCmsContentWidgetInterface
{

    /**
     * @param $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapContentWidgetParametersByTranslationKey($translationKey, LocaleTransfer $localeTransfer);

}
