<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileReferenceGenerator implements FileReferenceGeneratorInterface
{
    public function __construct(
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected SelfServicePortalConfig $config
    ) {
    }

    public function generateFileReference(FileTransfer $fileTransfer): string
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();
        $sequenceNumberSettingsTransfer
            ->setName($this->config->getCompanyFileSequenceNumberName())
            ->setPrefix($this->config->getCompanyFileSequenceNumberPrefix());

        return $this->sequenceNumberFacade->generate($sequenceNumberSettingsTransfer);
    }
}
