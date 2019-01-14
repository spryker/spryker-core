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
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
            $contentItemPlugin = $this->contentResolver->getContentItemPlugin($termKey);
            $contentTransfer->setContentTypeCandidateKey($contentItemPlugin->getTypeKey());
            $contentTransfer->setContentTermCandidateKey($contentItemPlugin->getTermKey());
        }
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

        $contentItemPlugin = $this->contentResolver->getContentItemPlugin($termKey);
        array_filter([]);
        return [
            'data_class' => ContentTransfer::class,
            ContentForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            ContentForm::OPTION_CONTENT_ITEM_REVERS_TRANSFORM => function (AbstractTransfer $abstractTransfer) {
                $arrayFilter = function ($input) use (&$arrayFilter) {
                    foreach ($input as &$value) {
                        if (is_array($value)) {
                            $value = $arrayFilter($value);
                        }
                    }

                    return array_filter($input);
                };
                $parameters = $arrayFilter($abstractTransfer->toArray());

                return (!empty($parameters)) ? json_encode($abstractTransfer->toArray()) : null;
            },
            ContentForm::OPTION_CONTENT_ITEM_TERM_FORM => $contentItemPlugin->getForm(),
            ContentForm::OPTION_CONTENT_ITEM_TRANSFORM => function (?string $params = null) use ($contentItemPlugin) {
                $params = json_decode((string)$params, true);

                return $contentItemPlugin->getTransferObject($params);
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
