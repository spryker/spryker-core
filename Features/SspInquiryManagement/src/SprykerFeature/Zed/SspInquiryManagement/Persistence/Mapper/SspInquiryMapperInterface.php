<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper;

use Generated\Shared\Transfer\SspInquiryTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry;
use Propel\Runtime\Collection\ObjectCollection;

interface SspInquiryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     *
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry
     */
    public function mapSspInquiryTransferToSspInquiryEntity(SspInquiryTransfer $sspInquiryTransfer, SpySspInquiry $sspInquiryEntity): SpySspInquiry;

    /**
     * @param \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry $sspInquiryEntity
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function mapSspInquiryEntityToSspInquiryTransfer(SpySspInquiry $sspInquiryEntity, SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $sspInquiryEntities
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function mapSspInquiryEntityCollectionToStateMachineItemTransfers(
        ObjectCollection $sspInquiryEntities
    ): array;
}
