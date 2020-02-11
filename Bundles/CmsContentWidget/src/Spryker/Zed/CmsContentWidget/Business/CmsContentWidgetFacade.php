<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetBusinessFactory getFactory()
 */
class CmsContentWidgetFacade extends AbstractFacade implements CmsContentWidgetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $content
     *
     * @return array
     */
    public function mapContentWidgetParameters($content)
    {
        return $this->getFactory()
            ->createCmsContentWidgetParameterMapper()
            ->map($content);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapContentWidgetParametersByTranslationKey($translationKey, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCmsContentWidgetParameterMapper()
            ->mapByTranslationKey($translationKey, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CmsContentWidgetConfigurationListTransfer
     */
    public function getContentWidgetConfigurationList()
    {
        return $this->getFactory()
            ->createCmsContentWidgetTemplateListProvider()
            ->getContentWidgetConfigurationList();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expandCmsBlockCollectorData(array $collectedData, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCmsBlockCollectorParameterMapExpander()
            ->map($collectedData, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expandCmsPageCollectorData(array $collectedData, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createCmsPageCollectorParameterMapExpander()
            ->map($collectedData, $localeTransfer);
    }
}
