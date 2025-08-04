<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModel;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;

class SspModelMapper
{
    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(protected UtilDateTimeServiceInterface $utilDateTimeService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspModel $sspModelEntity
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspModel
     */
    public function mapSspModelTransferToSpySspModelEntity(
        SspModelTransfer $sspModelTransfer,
        SpySspModel $sspModelEntity
    ): SpySspModel {
        $sspModelEntity->fromArray($sspModelTransfer->modifiedToArray());

        $sspModelEntity->setFkImageFile($sspModelTransfer->getImage()?->getIdFile());

        return $sspModelEntity;
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpySspModel $spySspModelEntity
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelTransfer
     */
    public function mapSpySspModelEntityToSspModelTransfer(
        SpySspModel $spySspModelEntity,
        SspModelTransfer $sspModelTransfer
    ): SspModelTransfer {
        $sspModelTransfer->fromArray($spySspModelEntity->toArray(), true);

        if ($spySspModelEntity->getFkImageFile()) {
            $sspModelTransfer->setImage(
                (new FileTransfer())->setIdFile($spySspModelEntity->getFkImageFile()),
            );
        }

        return $sspModelTransfer;
    }
}
