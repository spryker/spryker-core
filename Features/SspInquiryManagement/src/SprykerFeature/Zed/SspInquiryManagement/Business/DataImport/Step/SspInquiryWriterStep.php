<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step;

use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\DataSet\SspInquiryDataSetInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryWriterStep implements DataImportStepInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $config
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     */
    public function __construct(
        protected SspInquiryManagementConfig $config,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataImportException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!in_array($dataSet[SspInquiryDataSetInterface::TYPE], $this->config->getAllSelectableSspInquiryTypes())) {
            throw new DataImportException('Selectable Ssp Inquiry types are not allowed.');
        }

        $sequenceNumberSetting = $this->config->getSspInquirySequenceNumberSettings(
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
}
