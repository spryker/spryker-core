<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException;
use Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface;
use Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapperInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $merchantRelationshipRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface
     */
    protected $merchantRelationshipExpander;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapperInterface
     */
    protected $merchantRelationshipCriteriaMapper;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface>
     */
    protected $merchantRelationshipExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
     * @param \Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpanderInterface $merchantRelationshipExpander
     * @param \Spryker\Zed\MerchantRelationship\Business\Mapper\MerchantRelationshipCriteriaMapperInterface $merchantRelationshipCriteriaMapper
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface> $merchantRelationshipExpanderPlugins
     */
    public function __construct(
        MerchantRelationshipRepositoryInterface $merchantRelationshipRepository,
        MerchantRelationshipExpanderInterface $merchantRelationshipExpander,
        MerchantRelationshipCriteriaMapperInterface $merchantRelationshipCriteriaMapper,
        array $merchantRelationshipExpanderPlugins
    ) {
        $this->merchantRelationshipRepository = $merchantRelationshipRepository;
        $this->merchantRelationshipExpander = $merchantRelationshipExpander;
        $this->merchantRelationshipCriteriaMapper = $merchantRelationshipCriteriaMapper;
        $this->merchantRelationshipExpanderPlugins = $merchantRelationshipExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @throws \Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        $merchantRelationshipTransfer = $this->merchantRelationshipRepository->getMerchantRelationshipById(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        if (!$merchantRelationshipTransfer) {
            throw new MerchantRelationshipNotFoundException();
        }

        return $this->merchantRelationshipExpander->expandWithName($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireMerchantRelationshipKey();

        $merchantRelationshipTransfer = $this->merchantRelationshipRepository->findMerchantRelationshipByKey(
            $merchantRelationshipTransfer->getMerchantRelationshipKey(),
        );

        if (!$merchantRelationshipTransfer) {
            return null;
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return array<int>
     */
    public function getIdAssignedBusinessUnitsByMerchantRelationshipId(int $idMerchantRelationship): array
    {
        return $this->merchantRelationshipRepository->getIdAssignedBusinessUnitsByMerchantRelationshipId($idMerchantRelationship);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer|null $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer|\Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(
        ?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null,
        ?MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer = null
    ) {
        $isMerchantRelationshipCriteriaTransferProvided = $merchantRelationshipCriteriaTransfer !== null;
        $merchantRelationshipCriteriaTransfer = $this->getMerchantRelationshipCriteriaTransfer(
            $merchantRelationshipFilterTransfer,
            $merchantRelationshipCriteriaTransfer,
        );

        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipRepository->getMerchantRelationshipCollection(
            $merchantRelationshipCriteriaTransfer,
        );

        $merchantRelationshipTransfers = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipTransfer = $this->merchantRelationshipExpander->expandWithName($merchantRelationshipTransfer);
            $merchantRelationshipTransfer = $this->executeMerchantRelationshipExpanderPlugins($merchantRelationshipTransfer);
            $merchantRelationshipTransfers[] = $merchantRelationshipTransfer;
        }

        if (!$isMerchantRelationshipCriteriaTransferProvided) {
            return $merchantRelationshipTransfers;
        }

        return $merchantRelationshipCollectionTransfer->setMerchantRelationships(
            new ArrayObject($merchantRelationshipTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        return $this->merchantRelationshipRepository->getMerchantRelationshipById(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeMerchantRelationshipExpanderPlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        foreach ($this->merchantRelationshipExpanderPlugins as $merchantRelationshipExpanderPlugin) {
            $merchantRelationshipTransfer = $merchantRelationshipExpanderPlugin->expand($merchantRelationshipTransfer);
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer|null $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function getMerchantRelationshipCriteriaTransfer(
        ?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null,
        ?MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer = null
    ): MerchantRelationshipCriteriaTransfer {
        if ($merchantRelationshipFilterTransfer && !$merchantRelationshipCriteriaTransfer) {
            return $this->merchantRelationshipCriteriaMapper->mapMerchantRelationshipFilterToMerchantRelationshipCriteria(
                $merchantRelationshipFilterTransfer,
                new MerchantRelationshipCriteriaTransfer(),
            );
        }

        return $merchantRelationshipCriteriaTransfer ?? new MerchantRelationshipCriteriaTransfer();
    }
}
