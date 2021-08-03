<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Writer;

use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclEntitySegmentResponseTransfer;
use Propel\Runtime\ActiveQuery\PropelQuery;
use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface;

class AclEntitySegmentWriter implements AclEntitySegmentWriterInterface
{
    /**
     * @var \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface
     */
    protected $aclEntityEntityManager;

    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @param \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface $aclEntityEntityManager
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(
        AclEntityEntityManagerInterface $aclEntityEntityManager,
        AclEntityServiceInterface $aclEntityService
    ) {
        $this->aclEntityEntityManager = $aclEntityEntityManager;
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param \Generated\Shared\Transfer\AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AclEntitySegmentResponseTransfer
     */
    public function create(AclEntitySegmentRequestTransfer $aclEntitySegmentRequestTransfer): AclEntitySegmentResponseTransfer
    {
        /** @var string $entity */
        $entity = $aclEntitySegmentRequestTransfer->getEntity();
        $referenceColumnName = $this->aclEntityService->generateSegmentConnectorReferenceColumnName(
            PropelQuery::from($entity)->getTableMap()->getName()
        );

        $aclEntitySegmentRequestTransfer->setEntity(
            $this->aclEntityService->generateSegmentConnectorClassName(
                $aclEntitySegmentRequestTransfer->getEntityOrFail()
            )
        );

        $mappedEntityIds = [];
        foreach ($aclEntitySegmentRequestTransfer->getEntityIds() as $entityId) {
            $mappedEntityIds[$referenceColumnName][] = $entityId;
        }

        $aclEntitySegmentRequestTransfer->setEntityIds($mappedEntityIds);

        $aclEntitySegmentTransfer = $this->aclEntityEntityManager->createAclEntitySegment($aclEntitySegmentRequestTransfer);

        $segmentResponseTransfer = (new AclEntitySegmentResponseTransfer())
            ->setAclEntitySegment($aclEntitySegmentTransfer)
            ->setIsSuccess(true);

        return $segmentResponseTransfer;
    }
}
