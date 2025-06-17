<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyUserFileMapper
{
    /**
     * @var string
     */
    protected const ENTITY_NAME = 'company_user';

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile> $companyUserFileEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\FileAttachmentTransfer>
     */
    public function mapCompanyUserFileEntitiesToFileAttachmentTransfers(ObjectCollection $companyUserFileEntityCollection): array
    {
        $fileAttachmentTransfers = [];

        foreach ($companyUserFileEntityCollection as $companyUserFileEntity) {
            $fileAttachmentTransfers[] = $this->mapCompanyUserFileEntityToFileAttachmentTransfer($companyUserFileEntity);
        }

        return $fileAttachmentTransfers;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFile $companyUserFileEntity
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function mapCompanyUserFileEntityToFileAttachmentTransfer(SpyCompanyUserFile $companyUserFileEntity): FileAttachmentTransfer
    {
        $fileTransfer = (new FileTransfer())->setIdFile($companyUserFileEntity->getFkFile());

        return (new FileAttachmentTransfer())
            ->setEntityId($companyUserFileEntity->getFkCompanyUser())
            ->setFile($fileTransfer)
            ->setEntityName(static::ENTITY_NAME);
    }
}
