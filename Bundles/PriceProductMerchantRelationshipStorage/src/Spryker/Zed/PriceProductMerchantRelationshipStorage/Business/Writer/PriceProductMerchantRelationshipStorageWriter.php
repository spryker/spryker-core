<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Writer;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriterInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface;

class PriceProductMerchantRelationshipStorageWriter implements PriceProductMerchantRelationshipStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriterInterface
     */
    protected $priceProductAbstractStorageWriter;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriterInterface
     */
    protected $priceProductConcreteStorageWriter;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductAbstractStorageWriterInterface $priceProductAbstractStorageWriter
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceProductConcreteStorageWriterInterface $priceProductConcreteStorageWriter
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        PriceProductAbstractStorageWriterInterface $priceProductAbstractStorageWriter,
        PriceProductConcreteStorageWriterInterface $priceProductConcreteStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->priceProductAbstractStorageWriter = $priceProductAbstractStorageWriter;
        $this->priceProductConcreteStorageWriter = $priceProductConcreteStorageWriter;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventEntityTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade
            ->getEventTransferIds($eventEntityTransfers);

        $companyBusinessUnitIds = [];
        foreach ($merchantIds as $idMerchant) {
            $merchantRelationshipTransfers = $this->getMerchantRelationshipTransfersByIdMerchant($idMerchant);

            foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
                $companyBusinessUnitIds = array_merge($companyBusinessUnitIds, $this->getCompanyBusinessUnitIds($merchantRelationshipTransfer));
            }
        }

        $this->priceProductAbstractStorageWriter->publishByCompanyBusinessUnitIds($companyBusinessUnitIds);
        $this->priceProductConcreteStorageWriter->publishByCompanyBusinessUnitIds($companyBusinessUnitIds);
    }

    /**
     * @param int $idMerchant
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    protected function getMerchantRelationshipTransfersByIdMerchant(int $idMerchant): array
    {
        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer($idMerchant);

        $merchantRelationshipCollection = $this->merchantRelationshipFacade
            ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

        if ($merchantRelationshipCollection instanceof MerchantRelationshipCollectionTransfer) {
            return $merchantRelationshipCollection->getMerchantRelationships()->getArrayCopy();
        }

        return $merchantRelationshipCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return array<int>
     */
    protected function getCompanyBusinessUnitIds(MerchantRelationshipTransfer $merchantRelationshipTransfer): array
    {
        $companyBusinessUnitIds = [];
        $companyBusinessUnitIds[] = $merchantRelationshipTransfer->getFkCompanyBusinessUnit();
        foreach ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        return $companyBusinessUnitIds;
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function createMerchantRelationshipCriteriaTransfer(int $idMerchant): MerchantRelationshipCriteriaTransfer
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())->addIdMerchant($idMerchant);

        return (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);
    }
}
