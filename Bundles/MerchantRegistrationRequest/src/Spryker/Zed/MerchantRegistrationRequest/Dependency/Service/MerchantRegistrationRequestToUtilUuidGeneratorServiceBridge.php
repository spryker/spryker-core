<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Dependency\Service;

use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;

class MerchantRegistrationRequestToUtilUuidGeneratorServiceBridge implements MerchantRegistrationRequestToUtilUuidGeneratorServiceInterface
{
    /**
     * @var \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface
     */
    protected $utilUuidGeneratorService;

    /**
     * @param \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface $utilUuidGeneratorService
     */
    public function __construct($utilUuidGeneratorService)
    {
        $this->utilUuidGeneratorService = $utilUuidGeneratorService;
    }

    public function generateUniqueRandomId(IdGeneratorSettingsTransfer $idGeneratorSettingsTransfer): string
    {
        return $this->utilUuidGeneratorService->generateUniqueRandomId($idGeneratorSettingsTransfer);
    }
}
