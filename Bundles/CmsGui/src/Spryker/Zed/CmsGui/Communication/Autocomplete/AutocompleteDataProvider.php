<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Autocomplete;

use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class AutocompleteDataProvider implements AutocompleteDataProviderInterface
{
    public const SEARCH_LIMIT = 20;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param string $translationKey
     *
     * @return array
     */
    public function getAutocompleteDataForTranslationKey($translationKey)
    {
        $glossaryKeys = $this->cmsQueryContainer
            ->queryKeyWithTranslationByKey($translationKey)
            ->limit(static::SEARCH_LIMIT)
            ->find();

        $result = [];
        foreach ($glossaryKeys as $glossaryKeyEntity) {
            $translations = [];
            foreach ($glossaryKeyEntity->getSpyGlossaryTranslations() as $glossaryTranslationEntity) {
                $translations[$glossaryTranslationEntity->getFkLocale()] = $glossaryTranslationEntity->toArray();
            }

            $result[] = [
                'key' => $glossaryKeyEntity->getLabel(),
                'translations' => $translations,
            ];
        }

        return $result;
    }

    /**
     * @param string $translationValue
     *
     * @return array
     */
    public function getAutocompleteDataForTranslationValue($translationValue)
    {
        $glossaryTranslations = $this->cmsQueryContainer
            ->queryTranslationWithKeyByValue($translationValue)
            ->limit(static::SEARCH_LIMIT)
            ->find();

        $result = [];
        foreach ($glossaryTranslations as $glossaryTranslationEntity) {
            if (!isset($result[$glossaryTranslationEntity->getLabel()])) {
                $result[$glossaryTranslationEntity->getLabel()] = [
                    'key' => $glossaryTranslationEntity->getLabel(),
                    'translations' => [],
                ];
            }

            $result[$glossaryTranslationEntity->getLabel()]['translations'][$glossaryTranslationEntity->getFkLocale()] =
                $glossaryTranslationEntity->toArray();
        }

        return $result;
    }
}
