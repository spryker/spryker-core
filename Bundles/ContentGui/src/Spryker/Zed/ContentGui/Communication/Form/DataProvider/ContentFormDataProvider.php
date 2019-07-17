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
use Spryker\Shared\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Communication\Form\ContentForm;
use Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface;

class ContentFormDataProvider implements ContentFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface $contentResolver
     */
    protected $contentResolver;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ContentGui\Communication\Resolver\ContentResolverInterface $contentResolver
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ContentResolverInterface $contentResolver, ContentGuiToContentFacadeInterface $contentFacade, ContentGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->contentResolver = $contentResolver;
        $this->contentFacade = $contentFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $termKey
     * @param int|null $contentId
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    public function getData(string $termKey, ?int $contentId = null): ?ContentTransfer
    {
        if ($contentId !== null) {
            $contentTransfer = $this->contentFacade->findContentById($contentId);

            if (!$contentTransfer) {
                return null;
            }

            return $this->setAvailableLocales($contentTransfer);
        }

        $contentTransfer = new ContentTransfer();
        $contentPlugin = $this->contentResolver->getContentPlugin($termKey);
        $contentTransfer->setContentTypeKey($contentPlugin->getTypeKey());
        $contentTransfer->setContentTermKey($contentPlugin->getTermKey());

        return $this->setAvailableLocales($contentTransfer);
    }

    /**
     * @param string $termKey
     * @param \Generated\Shared\Transfer\ContentTransfer|null $contentTransfer
     *
     * @return array
     */
    public function getOptions(string $termKey, ?ContentTransfer $contentTransfer = null): array
    {
        if ($contentTransfer) {
            $termKey = $contentTransfer->getContentTermKey();
        }

        $contentPlugin = $this->contentResolver->getContentPlugin($termKey);

        return [
            'data_class' => ContentTransfer::class,
            ContentForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            ContentForm::OPTION_CONTENT_ITEM_FORM_PLUGIN => $contentPlugin,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    protected function setAvailableLocales(ContentTransfer $contentTransfer): ContentTransfer
    {
        $localizedContents = $this->getLocalizedContentList($contentTransfer->getLocalizedContents());
        $contentTransfer->setLocalizedContents(new ArrayObject());
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
        $defaultLocale->setLocaleName(ContentGuiConfig::DEFAULT_LOCALE_NAME);

        $locales = $this->localeFacade
            ->getLocaleCollection();

        $locales[$defaultLocale->getLocaleName()] = $defaultLocale;

        return $locales;
    }
}
