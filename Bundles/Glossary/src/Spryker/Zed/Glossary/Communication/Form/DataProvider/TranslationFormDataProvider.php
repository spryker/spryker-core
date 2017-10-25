<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Form\DataProvider;

use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Glossary\Communication\Form\TranslationForm;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

class TranslationFormDataProvider
{
    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface $glossaryQueryContainer
     */
    public function __construct(GlossaryQueryContainerInterface $glossaryQueryContainer)
    {
        $this->glossaryQueryContainer = $glossaryQueryContainer;
    }

    /**
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return array
     */
    public function getData($fkGlossaryKey, array $locales)
    {
        $data = [];

        $glossaryKeyEntity = $this->getGlossaryKey($fkGlossaryKey);
        $data[TranslationForm::FIELD_GLOSSARY_KEY] = $glossaryKeyEntity->getKey();

        $translationCollection = $this->getGlossaryKeyTranslations($fkGlossaryKey, $locales);

        if (!empty($translationCollection)) {
            foreach ($translationCollection as $translation) {
                $data[TranslationForm::FIELD_LOCALES][$translation[GlossaryQueryContainer::LOCALE]] = $translation[GlossaryQueryContainer::VALUE];
            }
        }

        return $data;
    }

    /**
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return array
     */
    protected function getGlossaryKeyTranslations($fkGlossaryKey, array $locales)
    {
        return $this->glossaryQueryContainer
            ->queryGlossaryKeyTranslationsByLocale($fkGlossaryKey, $locales)
            ->find()
            ->toArray(null, false, TableMap::TYPE_COLNAME);
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKey
     */
    protected function getGlossaryKey($fkGlossaryKey)
    {
        return $this->glossaryQueryContainer->queryKeys()->findOneByIdGlossaryKey($fkGlossaryKey);
    }
}
