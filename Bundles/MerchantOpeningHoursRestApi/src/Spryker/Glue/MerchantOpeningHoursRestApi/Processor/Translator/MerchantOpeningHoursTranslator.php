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
    public function translateMerchantOpeningHoursTransfers(array $merchantOpeningHoursStorageTransfers, string $localeName): array
    {
        $scheduleNoteGlossaryKeys = $this->getScheduleNoteGlossaryKeys($merchantOpeningHoursStorageTransfers);

        $translatedNotes = $this->glossaryStorageClient->translateBulk($scheduleNoteGlossaryKeys, $localeName);

        return $this->updateDateScheduleTransfers($merchantOpeningHoursStorageTransfers, $translatedNotes);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     *
     * @return string[]
     */
    protected function getScheduleNoteGlossaryKeys(array $merchantOpeningHoursStorageTransfers): array
    {
        $scheduleNoteGlossaryKeys = [];
        foreach ($merchantOpeningHoursStorageTransfers as $merchantOpeningHoursStorageTransfer) {
            foreach ($merchantOpeningHoursStorageTransfer->getDateSchedule() as $dateScheduleTransfer) {
                $scheduleNoteGlossaryKeys[] = $dateScheduleTransfer->getNoteGlossaryKey();
            }
        }

        return array_unique(array_filter($scheduleNoteGlossaryKeys));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     * @param string[] $translatedNotes
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function updateDateScheduleTransfers(array $merchantOpeningHoursStorageTransfers, array $translatedNotes): array
    {
        foreach ($merchantOpeningHoursStorageTransfers as $merchantOpeningHoursStorageTransfer) {
            foreach ($merchantOpeningHoursStorageTransfer->getDateSchedule() as $dateScheduleTransfer) {
                if (isset($translatedNotes[$dateScheduleTransfer->getNoteGlossaryKey()])) {
                    $dateScheduleTransfer->setNoteGlossaryKey($translatedNotes[$dateScheduleTransfer->getNoteGlossaryKey()]);
                }
            }
        }

        return $merchantOpeningHoursStorageTransfers;
    }
}
