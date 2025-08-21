<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SelfServicePortal\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SspModelBuilder;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SspModelHelper extends Module
{
    use DataCleanupHelperTrait;

    public function haveSspModel(array $seedData = []): SspModelTransfer
    {
        $sspModelTransfer = (new SspModelBuilder($seedData))->build();

        $sspModelEntity = SpySspModelQuery::create()
            ->filterByReference($sspModelTransfer->getReference())
            ->findOneOrCreate();

        $sspModelEntity
            ->setName($sspModelTransfer->getName())
            ->setReference($sspModelTransfer->getReference())
            ->setCode($sspModelTransfer->getCode())
            ->setImageUrl($sspModelTransfer->getImageUrl());

        if ($sspModelTransfer->getImage()) {
            $sspModelEntity->setFkImageFile($sspModelTransfer->getImageOrFail()->getIdFileOrFail());
        }

        if ($sspModelEntity->isNew() || $sspModelEntity->isModified()) {
            $sspModelEntity->save();
        }

        $sspModelTransfer->setIdSspModel($sspModelEntity->getIdSspModel());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspModelTransfer): void {
            $this->debug(sprintf('Deleting Ssp Model: %s', $sspModelTransfer->getIdSspModel()));
            SpySspModelQuery::create()->filterByIdSspModel($sspModelTransfer->getIdSspModel())->delete();
        });

        return $sspModelTransfer;
    }
}
