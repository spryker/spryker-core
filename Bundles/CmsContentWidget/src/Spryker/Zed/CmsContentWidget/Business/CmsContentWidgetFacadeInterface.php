<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business;

use Generated\Shared\Transfer\LocaleTransfer;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetBusinessFactory getFactory()
 */
interface CmsContentWidgetFacadeInterface
{
    /**
     * Specification:
     *  - Extracts twig functions arguments and maps to values which can be used when reading from yves store.
     *
     * @api
     *
     * @param string $content
     *
     * @return array
     */
    public function mapContentWidgetParameters($content);

    /**
     * Specification:
     *  - Finds translation content for given key and locale
     *  - Extracts twig functions arguments and maps to values which can be used when reading from yves store.
     *
     * @api
     *
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapContentWidgetParametersByTranslationKey($translationKey, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     *  - Reads all registered content widget plugin CmsConfig::getCmsContentWidgetConfigurationProviders
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CmsContentWidgetConfigurationListTransfer
     */
    public function getContentWidgetConfigurationList();

    /**
     * Specification:
     *  - Expands collector data with cms content widget parameter map
     *
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expandCmsBlockCollectorData(array $collectedData, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     *  - Expands collector data with cms content widget parameter map
     *
     * @api
     *
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function expandCmsPageCollectorData(array $collectedData, LocaleTransfer $localeTransfer);
}
