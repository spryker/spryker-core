<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Generated\Shared\Transfer\ContentAbstractProductTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Zed\ContentGui\Communication\Form\ContentForm;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface;
use Spryker\Zed\ContentItem\Form\AbstractProductListContentTermForm;

class ContentFormDataProvider
{
    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface
     */
    protected $localFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface $localFacade
     */
    public function __construct(ContentGuiToContentFacadeBridgeInterface $contentFacade, ContentGuiToLocaleFacadeBridgeInterface $localFacade)
    {
        $this->contentFacade = $contentFacade;
        $this->localFacade = $localFacade;
    }

    /**
     * @param int|null $contentId
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function getData(?int $contentId = null): ContentTransfer
    {
        if ($contentId) {
            $contentTransfer = $this->contentFacade->findContentById($contentId);
        } else {
            $contentTransfer = new ContentTransfer();

            foreach ($this->getAvailableLocales() as $locale) {
                $localizedContentTransfer = new LocalizedContentTransfer();
                $localizedContentTransfer->setLocaleName($locale->getLocaleName());
                $localizedContentTransfer->setFkLocale($locale->getIdLocale());

                $localizedContentTransfer->setParameters(json_encode([]));

                $contentTransfer->addLocalizedContent($localizedContentTransfer);
            }
        }

        return $contentTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ContentTransfer::class,
            ContentForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            ContentForm::OPTION_CONTENT_ITEM_ENTITY => 'AbstractProductList',
            ContentForm::OPTION_CONTENT_ITEM_TERM_FORM => AbstractProductListContentTermForm::class,
            ContentForm::OPTION_CONTENT_ITEM_TRANSFORM => function (?string $params = null) {
                $data = new ContentAbstractProductListTransfer();
                $params = json_decode((string)$params, true);
                if (empty($params)) {
                    $data->addSkus((new ContentAbstractProductTransfer()));
                } else {
                    foreach ($params as $param) {
                        $contentAbstractProductTransfer = new ContentAbstractProductTransfer();
                        $contentAbstractProductTransfer->setSku($param);
                        $data->addSkus($contentAbstractProductTransfer);
                    }
                }

                return $data;
            },
            ContentForm::OPTION_CONTENT_ITEM_REVERS_TRANSFORM => function (ContentAbstractProductListTransfer $contentAbstractProductListTransfer) {
                $data = [];
                foreach ($contentAbstractProductListTransfer->getSkus() as $contentAbstractProductTransfer) {
                    $data[] = $contentAbstractProductTransfer->getSku();
                }
                return json_encode($data);
            },
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales()
    {
        $defaultLocale = new LocaleTransfer();
        $defaultLocale->setLocaleName('Default locale');

        $locales = $this->localFacade
            ->getLocaleCollection();

        array_unshift($locales, $defaultLocale);

        return $locales;
    }
}
