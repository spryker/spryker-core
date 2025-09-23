<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider;

use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory;

class AttachedAssetTableDataProvider implements AttachedAssetTableDataProviderInterface
{
    public function __construct(
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade,
        protected SelfServicePortalCommunicationFactory $communicationFactory
    ) {
    }

    /**
     * @param int $idSspModel
     *
     * @return array<string, mixed>
     */
    public function getAttachedAssetTableData(int $idSspModel): array
    {
        $sspModelCriteriaTransfer = (new SspModelCriteriaTransfer())
            ->setSspModelConditions(
                (new SspModelConditionsTransfer())->setSspModelIds([$idSspModel]),
            );

        $sspModelCollectionTransfer = $this->selfServicePortalFacade->getSspModelCollection($sspModelCriteriaTransfer);

        if ($sspModelCollectionTransfer->getSspModels()->count() === 0) {
            return [];
        }

        /** @var \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer */
        $sspModelTransfer = $sspModelCollectionTransfer->getSspModels()->getIterator()->current();

        $attachedAssetsTable = $this->communicationFactory->createAttachedAssetsTable($sspModelTransfer);

        return $attachedAssetsTable->fetchData();
    }
}
