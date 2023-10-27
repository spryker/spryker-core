<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Saver;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferShipmentTypeCollectionRequestExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferFilterInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Validator\ProductOfferValidatorInterface;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface;

class ProductOfferShipmentTypeSaver implements ProductOfferShipmentTypeSaverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface
     */
    protected ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Validator\ProductOfferValidatorInterface
     */
    protected ProductOfferValidatorInterface $productOfferValidator;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferFilterInterface
     */
    protected ProductOfferFilterInterface $productOfferFilter;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface
     */
    protected ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferShipmentTypeCollectionRequestExpanderInterface
     */
    protected ProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferShipmentTypeCollectionRequestExpander;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Validator\ProductOfferValidatorInterface $productOfferValidator
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferFilterInterface $productOfferFilter
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferShipmentTypeCollectionRequestExpander
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     */
    public function __construct(
        ProductOfferShipmentTypeEntityManagerInterface $productOfferShipmentTypeEntityManager,
        ProductOfferValidatorInterface $productOfferValidator,
        ProductOfferFilterInterface $productOfferFilter,
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository,
        ProductOfferShipmentTypeCollectionRequestExpanderInterface $productOfferShipmentTypeCollectionRequestExpander,
        ProductOfferExtractorInterface $productOfferExtractor
    ) {
        $this->productOfferShipmentTypeEntityManager = $productOfferShipmentTypeEntityManager;
        $this->productOfferValidator = $productOfferValidator;
        $this->productOfferFilter = $productOfferFilter;
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
        $this->productOfferShipmentTypeCollectionRequestExpander = $productOfferShipmentTypeCollectionRequestExpander;
        $this->productOfferExtractor = $productOfferExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer
     */
    public function saveProductOfferShipmentTypes(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionResponseTransfer {
        $this->assertRequiredFields($productOfferShipmentTypeCollectionRequestTransfer);

        $productOfferShipmentTypeCollectionRequestTransfer = $this->productOfferShipmentTypeCollectionRequestExpander
            ->expandProductOfferShipmentTypeCollectionRequestTransfer($productOfferShipmentTypeCollectionRequestTransfer);
        $productOfferShipmentTypeCollectionResponseTransfer = (new ProductOfferShipmentTypeCollectionResponseTransfer())
            ->setProductOffers($productOfferShipmentTypeCollectionRequestTransfer->getProductOffers());

        $productOfferShipmentTypeCollectionResponseTransfer = $this->productOfferValidator->validate($productOfferShipmentTypeCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $productOfferShipmentTypeCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            if ($productOfferShipmentTypeCollectionRequestTransfer->getThrowExceptionOnValidation()) {
                /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
                $errorTransfer = $errorTransfers->getIterator()->current();

                throw new ProductOfferValidationException($errorTransfer->getMessageOrFail());
            }

            if ($productOfferShipmentTypeCollectionRequestTransfer->getIsTransactional()) {
                return $productOfferShipmentTypeCollectionResponseTransfer;
            }
        }

        [$validProductOfferTransfers, $invalidProductOfferTransfers] = $this->productOfferFilter
            ->filterProductOffersByValidity($productOfferShipmentTypeCollectionResponseTransfer);

        if ($validProductOfferTransfers->count()) {
            $validProductOfferTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validProductOfferTransfers) {
                return $this->executeSaveProductOfferShipmentTypesTransaction($validProductOfferTransfers);
            });
        }

        return $productOfferShipmentTypeCollectionResponseTransfer->setProductOffers(
            $this->mergeProductOffers($validProductOfferTransfers, $invalidProductOfferTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function executeSaveProductOfferShipmentTypesTransaction(ArrayObject $productOfferTransfers): ArrayObject
    {
        $productOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeConditionsTransfer())
            ->setProductOfferIds(
                $this->productOfferExtractor->extractProductOfferIdsFromProductOfferTransfers($productOfferTransfers),
            )
            ->setGroupByIdProductOffer(true);
        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())->setProductOfferShipmentTypeConditions(
            $productOfferShipmentTypeConditionsTransfer,
        );

        $productOfferShipmentTypeCollectionTransfer = $this->productOfferShipmentTypeRepository
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);
        $shipmentTypeIdsGroupedByIdProductOffer = $this->getShipmentTypeIdsGroupedByIdProductOffer($productOfferShipmentTypeCollectionTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $this->persistProductOfferShipmentTypes($productOfferTransfer, $shipmentTypeIdsGroupedByIdProductOffer);
        }

        return $productOfferTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param array<int, list<int>> $shipmentTypeIdsGroupedByIdProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function persistProductOfferShipmentTypes(
        ProductOfferTransfer $productOfferTransfer,
        array $shipmentTypeIdsGroupedByIdProductOffer
    ): ProductOfferTransfer {
        $requestedShipmentTypeIds = $this->productOfferExtractor->extractShipmentTypeIdsFromProductOfferTransfers(new ArrayObject([$productOfferTransfer]));
        $assignedShipmentTypeIds = $shipmentTypeIdsGroupedByIdProductOffer[$productOfferTransfer->getIdProductOfferOrFail()] ?? [];

        $shipmentTypeIdsToAssign = array_diff($requestedShipmentTypeIds, $assignedShipmentTypeIds);

        /** @var list<int> $shipmentTypeIdsToUnassign */
        $shipmentTypeIdsToUnassign = array_diff($assignedShipmentTypeIds, $requestedShipmentTypeIds);
        if ($shipmentTypeIdsToUnassign !== []) {
            $this->productOfferShipmentTypeEntityManager->deleteProductOfferShipmentTypes(
                $productOfferTransfer->getIdProductOfferOrFail(),
                $shipmentTypeIdsToUnassign,
            );
        }

        foreach ($shipmentTypeIdsToAssign as $idShipmentType) {
            $this->productOfferShipmentTypeEntityManager->createProductOfferShipmentType(
                $productOfferTransfer->getIdProductOfferOrFail(),
                $idShipmentType,
            );
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return array<int, list<int>>
     */
    protected function getShipmentTypeIdsGroupedByIdProductOffer(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): array {
        $shipmentTypeIdsGroupedByIdProductOffer = [];
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $idProductOffer = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            foreach ($productOfferShipmentTypeTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeIdsGroupedByIdProductOffer[$idProductOffer][] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            }
        }

        return $shipmentTypeIdsGroupedByIdProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer): void
    {
        $productOfferShipmentTypeCollectionRequestTransfer->requireProductOffers();
        $productOfferShipmentTypeCollectionRequestTransfer->requireIsTransactional();

        foreach ($productOfferShipmentTypeCollectionRequestTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfer->requireProductOfferReference();

            foreach ($productOfferTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeTransfer->requireUuid();
            }
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $validProductOfferTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $invalidProductOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function mergeProductOffers(
        ArrayObject $validProductOfferTransfers,
        ArrayObject $invalidProductOfferTransfers
    ): ArrayObject {
        foreach ($invalidProductOfferTransfers as $entityIdentifier => $invalidProductOfferTransfer) {
            $validProductOfferTransfers->offsetSet($entityIdentifier, $invalidProductOfferTransfer);
        }

        return $validProductOfferTransfers;
    }
}
