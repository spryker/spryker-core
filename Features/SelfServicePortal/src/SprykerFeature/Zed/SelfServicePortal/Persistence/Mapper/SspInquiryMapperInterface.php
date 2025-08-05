<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

interface SspInquiryMapperInterface
{
    public function mapSspInquiryTransferToSspInquiryEntity(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SpySspInquiry;

    public function mapSspInquiryEntityToSspInquiryTransfer(SpySspInquiry $sspInquiryEntity, SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $sspInquiryEntities
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function mapSspInquiryEntityCollectionToStateMachineItemTransfers(
        ObjectCollection $sspInquiryEntities
    ): array;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile> $sspInquiryFileEntities
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function mapSspInquiryFileEntitiesToSspInquiryCollectionTransfer(
        Collection $sspInquiryFileEntities,
        SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
    ): SspInquiryCollectionTransfer;
}
