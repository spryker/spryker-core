<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ContentAbstractProductListTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ContentGui\Communication\Form\ContentForm;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface;
use Spryker\Zed\ContentProductConnector\Form\AbstractProductListContentTermForm;

class ContentFormDataProvider implements ContentFormDataProviderInterface
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

        $contentTransfer = ($contentId !== null) ? $this->contentFacade->findContentById($contentId) : (new ContentTransfer());
        $localizedContents = $this->getIndexLocalizedContent($contentTransfer->getLocalizedContents());
        $contentTransfer->setLocalizedContents((new ArrayObject()));
        foreach ($this->getAvailableLocales() as $locale) {
            $localizedContentTransfer = new LocalizedContentTransfer();
            if (!empty($localizedContents[$locale->getIdLocale()])) {
                $localizedContentTransfer->fromArray($localizedContents[$locale->getIdLocale()]->toArray());
            }
            $localizedContentTransfer->setLocaleName($locale->getLocaleName());
            $localizedContentTransfer->setFkLocale($locale->getIdLocale());

            $contentTransfer->addLocalizedContent($localizedContentTransfer);
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
            ContentForm::OPTION_CONTENT_TERM_CANDIDATE_KEY => 'AbstractProductListTerm',
            ContentForm::OPTION_CONTENT_TYPE_CANDIDATE_KEY => 'AbstractProductListType',
            ContentForm::OPTION_CONTENT_ITEM_TERM_FORM => AbstractProductListContentTermForm::class,
            ContentForm::OPTION_CONTENT_ITEM_TRANSFORM => function (?string $params = null) {
                $data = new ContentAbstractProductListTransfer();
                $params = json_decode((string)$params, true);
                if (empty($params) || empty($params['skus'])) {
                    $data->setSkus(['', '']);
                } else {
                    $data->fromArray($params);
                }

                return $data;
            },
            ContentForm::OPTION_CONTENT_ITEM_REVERS_TRANSFORM => function (AbstractTransfer $abstractTransfer) {
                $entityParams = array_filter($abstractTransfer->toArray());

                return (!empty($entityParams)) ? json_encode($abstractTransfer->toArray()) : null;
            },
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedContentTransfer[] $localizedContents
     *
     * @return \Generated\Shared\Transfer\LocalizedContentTransfer[]
     */
    protected function getIndexLocalizedContent(ArrayObject $localizedContents): array
    {
        $indexLocalizedContents = [];
        foreach ($localizedContents as $localizedContent) {
            $indexLocalizedContents[$localizedContent->getFkLocale()] = $localizedContent;
        }

        return $indexLocalizedContents;
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

        $locales[$defaultLocale->getLocaleName()] = $defaultLocale;

        return $locales;
    }
}
