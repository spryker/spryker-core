<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile\DataSet\MerchantProfileDataSetInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;

class MerchantProfileWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $idMerchant = $dataSet[MerchantProfileDataSetInterface::ID_MERCHANT];
        $merchantProfileEntity = SpyMerchantProfileQuery::create()
            ->filterByFkMerchant($idMerchant)
            ->findOneOrCreate();

        $merchantProfileData = array_filter($dataSet->getArrayCopy());
        $merchantProfileEntity->fromArray($merchantProfileData);
        $merchantProfileEntity->save();

        $merchantProfileEntity = $this->saveGlossaryKeyAttributes($merchantProfileEntity, $dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES]);

        $merchantProfileEntity->save();

        $this->addPublishEvents(MerchantProfileEvents::ENTITY_SPY_MERCHANT_PROFILE_PUBLISH, $merchantProfileEntity->getIdMerchantProfile());
    }

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $merchantProfileEntity
     * @param array $glossaryKeyAttributes
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile
     */
    protected function saveGlossaryKeyAttributes(SpyMerchantProfile $merchantProfileEntity, array $glossaryKeyAttributes): SpyMerchantProfile
    {
        foreach ($glossaryKeyAttributes as $idLocale => $attributes) {
            $merchantProfileEntity = $this->saveGlossaryKeyAttributesForLocale($merchantProfileEntity, $attributes, $idLocale);
        }

        return $merchantProfileEntity;
    }

    /**
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $merchantProfileEntity
     * @param array $attributes
     * @param int $idLocale
     *
     * @return \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile
     */
    protected function saveGlossaryKeyAttributesForLocale(SpyMerchantProfile $merchantProfileEntity, array $attributes, int $idLocale): SpyMerchantProfile
    {
        $idMerchant = $merchantProfileEntity->getFkMerchant();

        foreach ($attributes as $attributeName => $attributeValue) {
            if (!$attributeValue) {
                continue;
            }
            if ($attributeName === MerchantProfileDataSetInterface::URL) {
                $this->addMerchantProfileUrl($merchantProfileEntity->getIdMerchantProfile(), $idLocale, $attributeValue);
                continue;
            }

            $merchantProfileEntity->fromArray([
                $attributeName => $this->generateMerchantGlossaryKey($attributeName, $idMerchant),
            ]);

            $glossaryFieldKey = $this->generateMerchantGlossaryKey($attributeName, $idMerchant);
            $glossaryKeyEntity = SpyGlossaryKeyQuery::create()
                ->filterByKey($glossaryFieldKey)
                ->findOneOrCreate();

            $glossaryKeyEntity->save();

            $glossaryTranslationEntity = SpyGlossaryTranslationQuery::create()
                ->filterByFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey())
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $glossaryTranslationEntity
                ->setValue($attributeValue);

            if ($glossaryTranslationEntity->isNew() || $glossaryTranslationEntity->isModified()) {
                $glossaryTranslationEntity->save();
            }

            $this->addPublishEvents(GlossaryStorageConfig::GLOSSARY_KEY_PUBLISH_WRITE, $glossaryKeyEntity->getIdGlossaryKey());
        }

        return $merchantProfileEntity;
    }

    /**
     * @param int $idMerchantProfile
     * @param int $idLocale
     * @param string $url
     *
     * @return void
     */
    protected function addMerchantProfileUrl(int $idMerchantProfile, int $idLocale, string $url): void
    {
        $urlEntity = SpyUrlQuery::create()
            ->filterByFkResourceMerchantProfile($idMerchantProfile)
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $urlEntity->setUrl($url);

        if ($urlEntity->isNew() || $urlEntity->isModified()) {
            $urlEntity->save();
        }

        $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
    }

    /**
     * @param string $field
     * @param int $idMerchant
     *
     * @return string
     */
    protected function generateMerchantGlossaryKey(string $field, int $idMerchant): string
    {
         return sprintf('merchant.%s.%s', $field, $idMerchant);
    }
}
