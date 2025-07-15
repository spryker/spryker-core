<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\DataSet\SspInquiryDataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryWriterStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const NAME_SSP_INQUIRY_REFERENCE = 'SspInquiryReference';

    /**
     * @var string
     */
    protected const SSP_INQUIRY_REFERENCE_PREFIX = 'INQR';

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     */
    public function __construct(
        protected SelfServicePortalConfig $config,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $allSelectableSspInquiryTypes = array_merge(...array_values($this->config->getSelectableSspInquiryTypes()));
        if (!in_array($dataSet[SspInquiryDataSetInterface::TYPE], $allSelectableSspInquiryTypes)) {
            throw new DataImportException('Selectable Ssp Inquiry types are not allowed.');
        }

        $sequenceNumberSetting = $this->getInquirySequenceNumberSettings(
            $dataSet[SspInquiryDataSetInterface::STORE],
        );
        $this->sequenceNumberFacade->generate($sequenceNumberSetting);

         $sspInquiryEntity = SpySspInquiryQuery::create()
            ->clear()
            ->filterByReference($dataSet[SspInquiryDataSetInterface::COLUMN_REFERENCE])
            ->findOneOrCreate();

        $isNew = $sspInquiryEntity->isNew();
         $sspInquiryEntity->fromArray($dataSet->getArrayCopy());
         $sspInquiryEntity->save();

        if (!$isNew) {
            return;
        }
        $dataSet[SspInquiryDataSetInterface::ID_SSP_INQUIRY] = $sspInquiryEntity->getIdSspInquiry();
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected function getInquirySequenceNumberSettings(string $storeName): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_SSP_INQUIRY_REFERENCE)
            ->setPrefix($this->createPrefix($storeName));
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    protected function createPrefix(string $storeName): string
    {
        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = $storeName;
        $sequenceNumberPrefixParts[] = static::SSP_INQUIRY_REFERENCE_PREFIX;

        return sprintf('%s--', implode('-', $sequenceNumberPrefixParts));
    }
}
