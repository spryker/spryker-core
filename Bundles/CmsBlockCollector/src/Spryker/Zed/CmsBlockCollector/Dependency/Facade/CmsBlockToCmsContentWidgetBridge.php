<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class CmsBlockToCmsContentWidgetBridge implements CmsBlockToCmsContentWidgetInterface
{

    /**
     * @var \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface
     */
    protected $cmsContentWidgetFacade;

    /**
     * @param \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface $cmsContentWidgetFacade
     */
    public function __construct($cmsContentWidgetFacade)
    {
        $this->cmsContentWidgetFacade = $cmsContentWidgetFacade;
    }

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapContentWidgetParametersByTranslationKey($translationKey, LocaleTransfer $localeTransfer)
    {
        return $this->cmsContentWidgetFacade->mapContentWidgetParametersByTranslationKey($translationKey, $localeTransfer);
    }

}
