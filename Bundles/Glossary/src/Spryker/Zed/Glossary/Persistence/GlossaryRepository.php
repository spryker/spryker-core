<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Generated\Shared\Transfer\GlossaryKeyTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryPersistenceFactory getFactory()
 */
class GlossaryRepository extends AbstractRepository implements GlossaryRepositoryInterface
{
    /**
     * @param string $glossaryKey
     * @param array<string> $localeIsoCodes
     *
     * @return array<\Generated\Shared\Transfer\TranslationTransfer>
     */
    public function getTranslationsByGlossaryKeyAndLocaleIsoCodes(string $glossaryKey, array $localeIsoCodes): array
    {
        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $glossaryTranslationQuery */
        $glossaryTranslationQuery = $this->getFactory()->createGlossaryTranslationQuery()
            ->useGlossaryKeyQuery()
                ->filterByKey($glossaryKey)
            ->endUse();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation[] $glossaryTranslationEntities */
        $glossaryTranslationEntities = $glossaryTranslationQuery
            ->useLocaleQuery()
                ->filterByLocaleName_In($localeIsoCodes)
            ->endUse()
            ->find();

        if ($glossaryTranslationEntities->count() === 0) {
            return [];
        }

        return $this->mapGlossaryTranslationEntitiesToTranslationTransfers($glossaryTranslationEntities);
    }

    /**
     * @param array<string> $glossaryKeys
     * @param array<string> $localeIsoCodes
     *
     * @return array<\Generated\Shared\Transfer\TranslationTransfer>
     */
    public function getTranslationsByGlossaryKeysAndLocaleIsoCodes(array $glossaryKeys, array $localeIsoCodes): array
    {
        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $glossaryTranslationQuery */
        $glossaryTranslationQuery = $this->getFactory()->createGlossaryTranslationQuery()
            ->useGlossaryKeyQuery()
                ->filterByKey_In($glossaryKeys)
            ->endUse();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation[] $glossaryTranslationEntities */
        $glossaryTranslationEntities = $glossaryTranslationQuery
            ->useLocaleQuery()
                ->filterByLocaleName_In($localeIsoCodes)
            ->endUse()
            ->find();

        if ($glossaryTranslationEntities->count() === 0) {
            return [];
        }

        return $this->mapGlossaryTranslationEntitiesToTranslationTransfers($glossaryTranslationEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation[] $glossaryTranslationEntities
     *
     * @return array<\Generated\Shared\Transfer\TranslationTransfer>
     */
    protected function mapGlossaryTranslationEntitiesToTranslationTransfers(ObjectCollection $glossaryTranslationEntities): array
    {
        $translationTransfers = [];
        $glossaryMapper = $this->getFactory()
            ->createGlossaryMapper();

        foreach ($glossaryTranslationEntities as $glossaryTranslationEntity) {
            $translationTransfer = new TranslationTransfer();
            $translationTransfer = $glossaryMapper
                ->mapGlossaryTranslationEntityToTranslationTransfer($glossaryTranslationEntity, $translationTransfer);

            $translationTransfers[] = $translationTransfer;
        }

        return $translationTransfers;
    }

    /**
     * @param array<string> $glossaryKeys
     *
     * @return array<\Generated\Shared\Transfer\GlossaryKeyTransfer>
     */
    public function getGlossaryKeyTransfersByGlossaryKeys(array $glossaryKeys): array
    {
        $glossaryKeyEntities = $this->getFactory()->createGlossaryKeyQuery()
            ->filterByKey_In($glossaryKeys)
            ->find();

        if ($glossaryKeyEntities->count() === 0) {
            return [];
        }

        return $this->mapGlossaryKeyEntitiesToGlossaryKeyTransfers($glossaryKeyEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Glossary\Persistence\SpyGlossaryKey[] $glossaryKeyEntities
     *
     * @return array<\Generated\Shared\Transfer\GlossaryKeyTransfer>
     */
    protected function mapGlossaryKeyEntitiesToGlossaryKeyTransfers(ObjectCollection $glossaryKeyEntities): array
    {
        $glossaryKeyTransfers = [];
        $glossaryMapper = $this->getFactory()->createGlossaryMapper();

        foreach ($glossaryKeyEntities as $glossaryKeyEntity) {
            $glossaryKeyTransfer = new GlossaryKeyTransfer();
            $glossaryKeyTransfer = $glossaryMapper
                ->mapGlossaryKeyEntityToGlossaryKeyTransfer($glossaryKeyEntity, $glossaryKeyTransfer);

            $glossaryKeyTransfers[] = $glossaryKeyTransfer;
        }

        return $glossaryKeyTransfers;
    }
}
