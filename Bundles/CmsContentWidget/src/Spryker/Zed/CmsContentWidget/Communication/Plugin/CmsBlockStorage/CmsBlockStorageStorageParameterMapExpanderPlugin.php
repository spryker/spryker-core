<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication\Plugin\CmsBlockStorage;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\SpyCmsBlockTransfer;
use Spryker\Shared\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidget\Communication\CmsContentWidgetCommunicationFactory getFactory()
 */
class CmsBlockStorageStorageParameterMapExpanderPlugin extends AbstractPlugin implements CmsBlockStorageDataExpanderPluginInterface
{
    /**
     * @api
     *
     * @param array $collectedData
     *
     * @return array
     */
    public function expand(array $collectedData)
    {
        //TODO Fix this for all locales
        $locale = Store::getInstance()->getCurrentLocale();
        $placeholders = $this->gePlaceholders($collectedData);

        $cmsBlockData['placeholders'] = $placeholders;
        $localeTransfer = (new LocaleTransfer())->setLocaleName($locale);
        $data = $this->getFacade()->expandCmsBlockCollectorData($cmsBlockData, $localeTransfer);
        $collectedData[CmsContentWidgetConfig::CMS_CONTENT_WIDGET_PARAMETER_MAP] = $data[CmsContentWidgetConfig::CMS_CONTENT_WIDGET_PARAMETER_MAP];

        return $collectedData;
    }

    /**
     * @param array $collectedData
     *
     * @return array
     */
    protected function gePlaceholders(array $collectedData)
    {
        $placeholders = [];
        $spyCmsBlockTransfer = (new SpyCmsBlockTransfer())->fromArray($collectedData);
        foreach ($spyCmsBlockTransfer->getSpyCmsBlockGlossaryKeyMappings() as $keyMapping) {
            $placeholders[$keyMapping->getPlaceholder()] = $keyMapping->getGlossaryKey()->getKey();
        }

        return $placeholders;
    }
}
