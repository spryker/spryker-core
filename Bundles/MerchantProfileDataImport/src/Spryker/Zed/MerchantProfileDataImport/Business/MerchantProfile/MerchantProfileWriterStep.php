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
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantProfileDataImport\Business\MerchantProfile\DataSet\MerchantProfileDataSetInterface;

class MerchantProfileWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\GlossaryStorage\GlossaryStorageConfig::GLOSSARY_KEY_PUBLISH_WRITE
     * @var string
     */
    protected const GLOSSARY_KEY_PUBLISH_WRITE = 'Glossary.key.publish';

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

        $this->addPublishEvents(MerchantEvents::MERCHANT_PUBLISH, $merchantProfileEntity->getFkMerchant());
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

            $this->addPublishEvents(static::GLOSSARY_KEY_PUBLISH_WRITE, $glossaryKeyEntity->getIdGlossaryKey());
        }

        return $merchantProfileEntity;
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
