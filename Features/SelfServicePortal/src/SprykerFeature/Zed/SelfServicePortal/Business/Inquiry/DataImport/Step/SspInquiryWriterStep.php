<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step;

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
        if (!in_array($dataSet[SspInquiryDataSetInterface::TYPE], $this->config->getAllSelectableInquiryTypes())) {
            throw new DataImportException('Selectable Ssp Inquiry types are not allowed.');
        }

        $sequenceNumberSetting = $this->config->getInquirySequenceNumberSettings(
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
