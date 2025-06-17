<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyBusinessUnitFileMapper
{
    /**
     * @var string
     */
    protected const ENTITY_NAME = 'company_business_unit';

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile> $companyBusinessUnitFileEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\FileAttachmentTransfer>
     */
    public function mapCompanyBusinessUnitFileEntitiesToFileAttachmentTransfers(ObjectCollection $companyBusinessUnitFileEntityCollection): array
    {
        $fileAttachmentTransfers = [];

        foreach ($companyBusinessUnitFileEntityCollection as $companyBusinessUnitFileEntity) {
            $fileAttachmentTransfers[] = $this->mapCompanyBusinessUnitFileEntityToFileAttachmentTransfer($companyBusinessUnitFileEntity);
        }

        return $fileAttachmentTransfers;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFile $companyBusinessUnitFileEntity
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function mapCompanyBusinessUnitFileEntityToFileAttachmentTransfer(
        SpyCompanyBusinessUnitFile $companyBusinessUnitFileEntity
    ): FileAttachmentTransfer {
        $fileTransfer = (new FileTransfer())->setIdFile($companyBusinessUnitFileEntity->getFkFile());

        return (new FileAttachmentTransfer())
            ->setEntityId($companyBusinessUnitFileEntity->getFkCompanyBusinessUnit())
            ->setFile($fileTransfer)
            ->setEntityName(static::ENTITY_NAME);
    }
}
