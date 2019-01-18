<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Zed\ContentGui\Communication\Form\ContentForm;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface;

class ContentFormDataProvider implements ContentFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface $contentResolver
     */
    protected $contentResolver;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface
     */
    protected $localFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface $contentResolver
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToContentFacadeBridgeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToLocaleFacadeBridgeInterface $localFacade
     */
    public function __construct(ContentResolverInterface $contentResolver, ContentGuiToContentFacadeBridgeInterface $contentFacade, ContentGuiToLocaleFacadeBridgeInterface $localFacade)
    {
        $this->contentResolver = $contentResolver;
        $this->contentFacade = $contentFacade;
        $this->localFacade = $localFacade;
    }

    /**
     * @param string $termKey
     * @param int|null $contentId
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function getData(string $termKey, ?int $contentId = null): ContentTransfer
    {
        if ($contentId !== null) {
            $contentTransfer = $this->contentFacade->findContentById($contentId);
        } else {
            $contentTransfer = new ContentTransfer();
            $contentPlugin = $this->contentResolver->getContentPlugin($termKey);
            $contentTransfer->setContentTypeCandidateKey($contentPlugin->getTypeKey());
            $contentTransfer->setContentTermCandidateKey($contentPlugin->getTermKey());
        }
        $localizedContents = $this->getLocalizedContentList($contentTransfer->getLocalizedContents());
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
     * @param string $termKey
     * @param int|null $contentId
     *
     * @return array
     */
    public function getOptions(string $termKey, ?int $contentId = null): array
    {
        if ($contentId !== null) {
            $termKey = $this->contentFacade->findContentById($contentId)->getContentTermCandidateKey();
        }

        $contentPlugin = $this->contentResolver->getContentPlugin($termKey);

        return [
            'data_class' => ContentTransfer::class,
            ContentForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            ContentForm::OPTION_CONTENT_ITEM_FORM_PLUGIN => $contentPlugin,
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedContentTransfer[] $localizedContents
     *
     * @return \Generated\Shared\Transfer\LocalizedContentTransfer[]
     */
    protected function getLocalizedContentList(ArrayObject $localizedContents): array
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
    protected function getAvailableLocales(): array
    {
        $defaultLocale = new LocaleTransfer();
        $defaultLocale->setLocaleName('Default locale');

        $locales = $this->localFacade
            ->getLocaleCollection();

        $locales[$defaultLocale->getLocaleName()] = $defaultLocale;

        return $locales;
    }
}
