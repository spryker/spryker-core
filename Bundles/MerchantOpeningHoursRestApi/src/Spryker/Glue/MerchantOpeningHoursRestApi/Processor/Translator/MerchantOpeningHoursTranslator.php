<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator;

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
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    public function getMerchantOpeningHoursTransfersWithTranslatedNotes(array $merchantOpeningHoursStorageTransfers, string $localeName): array
    {
        $scheduleNotes = [];
        foreach ($merchantOpeningHoursStorageTransfers as $merchantOpeningHoursStorageTransfer) {
            foreach ($merchantOpeningHoursStorageTransfer->getDateSchedule() as $dateScheduleTransfer) {
                $scheduleNotes[] = $dateScheduleTransfer->getNote();
            }
        }

        $translatedNotes = $this->glossaryStorageClient->translateBulk($scheduleNotes, $localeName);

        foreach ($merchantOpeningHoursStorageTransfers as $merchantOpeningHoursStorageTransfer) {
            foreach ($merchantOpeningHoursStorageTransfer->getDateSchedule() as $dateScheduleTransfer) {
                $dateScheduleTransfer->setNote($translatedNotes[$dateScheduleTransfer->getNote()]);
            }
        }

        return $merchantOpeningHoursStorageTransfers;
    }
}
