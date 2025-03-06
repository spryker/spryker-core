<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyFileMapper
{
    /**
     * @var string
     */
    protected const ENTITY_NAME = 'company';

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile> $companyFileEntityCollection
     *
     * @return array<\Generated\Shared\Transfer\FileAttachmentTransfer>
     */
    public function mapCompanyFileEntitiesToFileAttachmentTransfers(ObjectCollection $companyFileEntityCollection): array
    {
        $fileAttachmentTransfers = [];

        foreach ($companyFileEntityCollection as $companyFileEntity) {
            $fileAttachmentTransfers[] = $this->mapCompanyFileEntityToFileAttachmentTransfer($companyFileEntity);
        }

        return $fileAttachmentTransfers;
    }

    /**
     * @param \Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile $companyFileEntity
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    protected function mapCompanyFileEntityToFileAttachmentTransfer(SpyCompanyFile $companyFileEntity): FileAttachmentTransfer
    {
        $fileTransfer = (new FileTransfer())->setIdFile($companyFileEntity->getFkFile());

        return (new FileAttachmentTransfer())
            ->setEntityId($companyFileEntity->getFkCompany())
            ->setFile($fileTransfer)
            ->setEntityName(static::ENTITY_NAME);
    }
}
