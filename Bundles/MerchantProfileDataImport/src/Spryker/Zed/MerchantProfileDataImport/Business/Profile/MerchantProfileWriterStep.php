<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Profile;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProfileDataImport\Business\Profile\DataSet\MerchantProfileDataSetInterface;

class MerchantProfileWriterStep implements DataImportStepInterface
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

        $merchantProfileEntity
            ->setContactPersonRole($dataSet[MerchantProfileDataSetInterface::CONTENT_PERSON_ROLE])
            ->setContactPersonTitle($dataSet[MerchantProfileDataSetInterface::CONTENT_PERSON_TITLE])
            ->setContactPersonFirstName($dataSet[MerchantProfileDataSetInterface::CONTENT_PERSON_FIRST_NAME])
            ->setContactPersonLastName($dataSet[MerchantProfileDataSetInterface::CONTENT_PERSON_LAST_NAME])
            ->setContactPersonPhone($dataSet[MerchantProfileDataSetInterface::CONTENT_PERSON_PHONE])
            ->setBannerUrl($dataSet[MerchantProfileDataSetInterface::BANNER_URL])
            ->setLogoUrl($dataSet[MerchantProfileDataSetInterface::LOGO_URL])
            ->setPublicEmail($dataSet[MerchantProfileDataSetInterface::PUBLIC_EMAIL])
            ->setIsActive($dataSet[MerchantProfileDataSetInterface::IS_ACTIVE])

            ->setDescriptionGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::DESCRIPTION_GLOSSARY_KEY, $idMerchant))
            ->setBannerUrlGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::BANNER_URL_GLOSSARY_KEY, $idMerchant))
            ->setDeliveryTimeGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::DELIVERY_TIME_GLOSSARY, $idMerchant))
            ->setTermsConditionsGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::TERMS_CONDITIONS_GLOSSARY_KEY, $idMerchant))
            ->setCancellationPolicyGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::CANCELLATION_POLICY_GLOSSARY_KEY, $idMerchant))
            ->setImprintGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::IMPRINT_GLOSSARY_KEY, $idMerchant))
            ->setDataPrivacyGlossaryKey($this->generateMerchantGlossaryKey(MerchantProfileDataSetInterface::DATA_PRIVACY_GLOSSARY_KEY, $idMerchant))
            ->save();

        $this->saveGlossaryKeyAttributes($idMerchant, $dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES]);
    }

    /**
     * @param int $idMerchant
     * @param array $glossaryKeyAttributes
     *
     * @return void
     */
    protected function saveGlossaryKeyAttributes(int $idMerchant, array $glossaryKeyAttributes): void
    {
        foreach ($glossaryKeyAttributes as $idLocale => $attributes) {
            foreach ($attributes as $attributeName => $attributeValue) {
                $glossaryKeyEntity = SpyGlossaryKeyQuery::create()
                ->filterByKey($this->generateMerchantGlossaryKey($attributeName, $idMerchant))
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
            }
        }
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
     * @param string $merchantKey
     *
     * @return string
     */
    protected function generateMerchantGlossaryKey(string $field, string $merchantKey): string
    {
         return sprintf('merchant.%s.%s', $field, $merchantKey);
    }
}
