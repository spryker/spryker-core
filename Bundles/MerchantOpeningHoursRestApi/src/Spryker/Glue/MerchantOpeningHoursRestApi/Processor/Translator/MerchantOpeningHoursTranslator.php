<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToGlossaryStorageClientInterface;

class MerchantOpeningHoursTranslator implements MerchantOpeningHoursTranslatorInterface
{
    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(MerchantOpeningHoursRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    public function getMerchantOpeningHoursTransferWithTranslatedNotes(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $localeName
    ): MerchantOpeningHoursStorageTransfer {
        $scheduleNotes = [];
        $dateScheduleTransfers = $merchantOpeningHoursStorageTransfer->getDateSchedule();
        foreach ($dateScheduleTransfers as $dateScheduleTransfer) {
            $scheduleNotes[] = $dateScheduleTransfer->getNote();
        }

        $translatedNotes = $this->glossaryStorageClient->translateBulk($scheduleNotes, $localeName);
        foreach ($dateScheduleTransfers as $dateScheduleTransfer) {
            $dateScheduleTransfer->setNote($translatedNotes[$dateScheduleTransfer->getNote()]);
        }

        return $merchantOpeningHoursStorageTransfer;
    }
}
