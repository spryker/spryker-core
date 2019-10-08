<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Profile;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\MerchantProfile\Dependency\MerchantProfileEvents;
use Spryker\Zed\MerchantProfileDataImport\Business\Profile\DataSet\MerchantProfileDataSetInterface;

class MerchantProfileWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $idMerchant = $dataSet[MerchantProfileDataSetInterface::ID_MERCHANT];
        $merchantProfileEntity = SpyMerchantProfileQuery::create()
            ->filterByFkMerchant($idMerchant)
            ->findOneOrCreate();

        $merchantProfileData = array_filter($dataSet->getArrayCopy());
        $merchantProfileEntity->fromArray($merchantProfileData);
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
        $idMerchant = $merchantProfileEntity->getFkMerchant();
        foreach ($glossaryKeyAttributes as $idLocale => $attributes) {
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
                $this->addPublishEvents(GlossaryEvents::GLOSSARY_KEY_PUBLISH, $glossaryKeyEntity->getIdGlossaryKey());
            }
        }

        return $merchantProfileEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        $this->validateSimpleRequiredDataSet($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateSimpleRequiredDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateRequireDataSetByKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequireDataSetByKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException('"' . $requiredDataSetKey . '" is required.');
        }
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
