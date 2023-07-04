<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Saver;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilterInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidatorInterface;
use Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface;
use Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface;

class ProductOfferServiceSaver implements ProductOfferServiceSaverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface
     */
    protected ProductOfferServicePointEntityManagerInterface $productOfferServicePointEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidatorInterface
     */
    protected ProductOfferValidatorInterface $productOfferValidator;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilterInterface
     */
    protected ProductOfferFilterInterface $productOfferFilter;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface
     */
    protected ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpanderInterface
     */
    protected ProductOfferExpanderInterface $productOfferExpander;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface $productOfferServicePointEntityManager
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidatorInterface $productOfferValidator
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilterInterface $productOfferFilter
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpanderInterface $productOfferExpander
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     */
    public function __construct(
        ProductOfferServicePointEntityManagerInterface $productOfferServicePointEntityManager,
        ProductOfferValidatorInterface $productOfferValidator,
        ProductOfferFilterInterface $productOfferFilter,
        ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository,
        ProductOfferExpanderInterface $productOfferExpander,
        ProductOfferExtractorInterface $productOfferExtractor
    ) {
        $this->productOfferServicePointEntityManager = $productOfferServicePointEntityManager;
        $this->productOfferValidator = $productOfferValidator;
        $this->productOfferFilter = $productOfferFilter;
        $this->productOfferServicePointRepository = $productOfferServicePointRepository;
        $this->productOfferExpander = $productOfferExpander;
        $this->productOfferExtractor = $productOfferExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    public function saveProductOfferServices(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionResponseTransfer {
        $this->assertRequiredFields($productOfferServiceCollectionRequestTransfer);

        $productOfferServiceCollectionRequestTransfer = $this->expandProductOfferServiceCollectionRequestTransfer($productOfferServiceCollectionRequestTransfer);
        $productOfferServiceCollectionResponseTransfer = (new ProductOfferServiceCollectionResponseTransfer())
            ->setProductOffers($productOfferServiceCollectionRequestTransfer->getProductOffers());

        $productOfferServiceCollectionResponseTransfer = $this->productOfferValidator->validate($productOfferServiceCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $productOfferServiceCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count()) {
            if ($productOfferServiceCollectionRequestTransfer->getThrowExceptionOnValidation()) {
                /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
                $errorTransfer = $errorTransfers->getIterator()->current();

                throw new ProductOfferValidationException($errorTransfer->getMessageOrFail());
            }

            if ($productOfferServiceCollectionRequestTransfer->getIsTransactional()) {
                return $productOfferServiceCollectionResponseTransfer;
            }
        }

        [$validProductOfferTransfers, $invalidProductOfferTransfers] = $this->productOfferFilter
            ->filterProductOffersByValidity($productOfferServiceCollectionResponseTransfer);

        if ($validProductOfferTransfers->count()) {
            $validProductOfferTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validProductOfferTransfers) {
                return $this->executeSaveProductOfferServicesTransaction($validProductOfferTransfers);
            });
        }

        return $productOfferServiceCollectionResponseTransfer->setProductOffers(
            $this->productOfferFilter->mergeProductOffers($validProductOfferTransfers, $invalidProductOfferTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function executeSaveProductOfferServicesTransaction(ArrayObject $productOfferTransfers): ArrayObject
    {
        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->setProductOfferIds(
                $this->productOfferExtractor->extractProductOfferIdsFromProductOfferTransfers($productOfferTransfers),
            )
            ->setGroupByIdProductOffer(true);
        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        $productOfferServiceCollectionTransfer = $this->productOfferServicePointRepository->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
        $serviceIdsGroupedByIdProductOffer = $this->getServiceIdsGroupedByIdProductOffer($productOfferServiceCollectionTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $this->persistProductOfferServices($productOfferTransfer, $serviceIdsGroupedByIdProductOffer);
        }

        return $productOfferTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param array<int, list<int>> $serviceIdsGroupedByIdProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function persistProductOfferServices(
        ProductOfferTransfer $productOfferTransfer,
        array $serviceIdsGroupedByIdProductOffer
    ): ProductOfferTransfer {
        $requestedServiceIds = $this->productOfferExtractor->extractServiceIdsFromProductOfferTransfers(new ArrayObject([$productOfferTransfer]));
        $assignedServiceIds = $serviceIdsGroupedByIdProductOffer[$productOfferTransfer->getIdProductOfferOrFail()] ?? [];

        $serviceIdsToAssign = array_diff($requestedServiceIds, $assignedServiceIds);

        /** @var list<int> $serviceIdsToUnassign */
        $serviceIdsToUnassign = array_diff($assignedServiceIds, $requestedServiceIds);
        if ($serviceIdsToUnassign !== []) {
            $this->productOfferServicePointEntityManager->deleteProductOfferServicesByIdProductOfferAndServiceIds(
                $productOfferTransfer->getIdProductOfferOrFail(),
                $serviceIdsToUnassign,
            );
        }

        foreach ($serviceIdsToAssign as $idService) {
            $productOfferServiceTransfer = (new ProductOfferServiceTransfer())
                ->setIdProductOffer($productOfferTransfer->getIdProductOfferOrFail())
                ->setIdService($idService);

            $this->productOfferServicePointEntityManager->createProductOfferService($productOfferServiceTransfer);
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return array<int, list<int>>
     */
    protected function getServiceIdsGroupedByIdProductOffer(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): array {
        $serviceIdsGroupedByIdProductOffer = [];
        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $idProductOffer = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
                $serviceIdsGroupedByIdProductOffer[$idProductOffer][] = $serviceTransfer->getIdServiceOrFail();
            }
        }

        return $serviceIdsGroupedByIdProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer): void
    {
        $productOfferServiceCollectionRequestTransfer->requireProductOffers();
        $productOfferServiceCollectionRequestTransfer->requireIsTransactional();

        foreach ($productOfferServiceCollectionRequestTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfer->requireProductOfferReference();

            foreach ($productOfferTransfer->getServices() as $serviceTransfer) {
                $serviceTransfer->requireUuid();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer
     */
    protected function expandProductOfferServiceCollectionRequestTransfer(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionRequestTransfer {
        $productOfferServiceCollectionRequestTransfer = $this->productOfferExpander->expandProductOfferServiceCollectionRequestWithProductOffersIds(
            $productOfferServiceCollectionRequestTransfer,
        );

        return $this->productOfferExpander->expandProductOfferServiceCollectionRequestServicesWithServicePoints(
            $productOfferServiceCollectionRequestTransfer,
        );
    }
}
