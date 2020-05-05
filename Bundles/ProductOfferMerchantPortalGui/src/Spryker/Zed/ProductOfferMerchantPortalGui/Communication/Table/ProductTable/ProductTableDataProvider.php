<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductTableDataProvider extends AbstractTableDataProvider
{
    protected const COLUMN_DATA_STATUS_ACTIVE = 'Active';
    protected const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface
     */
    protected $productOfferMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    protected $productNameBuilder;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    private $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    private $localeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface
     */
    private $requestToGuiTableDataRequestHydrator;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface $productNameBuilder
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface $requestToGuiTableDataRequestHydrator
     */
    public function __construct(
        ProductOfferMerchantPortalGuiRepositoryInterface $productOfferMerchantPortalGuiRepository,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService,
        ProductNameBuilderInterface $productNameBuilder,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        RequestToGuiTableDataRequestHydratorInterface $requestToGuiTableDataRequestHydrator
    ) {
        $this->productOfferMerchantPortalGuiRepository = $productOfferMerchantPortalGuiRepository;
        $this->translatorFacade = $translatorFacade;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->productNameBuilder = $productNameBuilder;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->requestToGuiTableDataRequestHydrator = $requestToGuiTableDataRequestHydrator;
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface
     */
    protected function getRequestToGuiTableDataRequestHydrator(): RequestToGuiTableDataRequestHydratorInterface
    {
        return $this->requestToGuiTableDataRequestHydrator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createPersistenceCriteria(Request $request): AbstractTransfer
    {
        $criteria = new ProductTableCriteriaTransfer();
        $criteria->setMerchantUser($this->merchantUserFacade->getCurrentMerchantUser());
        $criteria->setLocale($this->localeFacade->getCurrentLocale());

        return $criteria;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\ProductTableCriteriaTransfer $persistenceCriteria
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function fetchData(AbstractTransfer $persistenceCriteria): GuiTableDataTransfer
    {
        if (!$persistenceCriteria instanceof ProductTableCriteriaTransfer) {
            throw new LogicException(sprintf(
                '%s expects %s as a criteria, %s given.',
                static::class,
                ProductTableCriteriaTransfer::class,
                get_class($persistenceCriteria)
            ));
        }

        $productTableDataTransfer = $this->productOfferMerchantPortalGuiRepository->getProductTableData($persistenceCriteria);
        $productTableDataArray = [];

        foreach ($productTableDataTransfer->getRows() as $productTableRowDataTransfer) {
            $productTableDataArray[] = [
                ProductTable::COL_KEY_SKU => $productTableRowDataTransfer->getSku(),
                ProductTable::COL_KEY_NAME => $this->getNameColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_STORES => $this->getStoresColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_IMAGE => $productTableRowDataTransfer->getImage(),
                ProductTable::COL_KEY_STATUS => $this->getStatusColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_VALID_FROM => $this->getFormattedDateTime($productTableRowDataTransfer->getValidFrom()),
                ProductTable::COL_KEY_VALID_TO => $this->getFormattedDateTime($productTableRowDataTransfer->getValidTo()),
                ProductTable::COL_KEY_OFFERS => $productTableRowDataTransfer->getOffersCount(),
            ];
        }

        $paginationTransfer = $productTableDataTransfer->getPagination();

        return (new GuiTableDataTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): ?string
    {
        $productConcreteTransfer = $this->createProductConcreteTransfer($productTableRowDataTransfer);
        $productAbstractTransfer = $this->createProductAbstractTransfer($productTableRowDataTransfer);

        return $this->productNameBuilder->buildProductName($productConcreteTransfer, $productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(ProductTableRowDataTransfer $productTableRowDataTransfer): ProductConcreteTransfer
    {
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productTableRowDataTransfer->getProductConcreteLocalizedAttributes())
            ->setName($productTableRowDataTransfer->getName());

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setAttributes($productTableRowDataTransfer->getProductConcreteAttributes())
            ->setLocalizedAttributes(new ArrayObject([$localizedAttributesTransfer]));

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(ProductTableRowDataTransfer $productTableRowDataTransfer): ProductAbstractTransfer
    {
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productTableRowDataTransfer->getProductAbstractLocalizedAttributes());

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setAttributes($productTableRowDataTransfer->getProductAbstractAttributes())
            ->setLocalizedAttributes(new ArrayObject([$localizedAttributesTransfer]));

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string
     */
    protected function getStoresColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer)
    {
        $storesString = $productTableRowDataTransfer->getStores();
        $stores = explode(',', $storesString);

        return implode(', ', $stores);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string
     */
    protected function getStatusColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): string
    {
        $isActiveColumnData = $productTableRowDataTransfer->getIsActive()
            ? static::COLUMN_DATA_STATUS_ACTIVE
            : static::COLUMN_DATA_STATUS_INACTIVE;

        return $this->translatorFacade->trans($isActiveColumnData);
    }

    /**
     * @param string|null $dateTime
     *
     * @return string|null
     */
    protected function getFormattedDateTime(?string $dateTime): ?string
    {
        return $dateTime ? $this->utilDateTimeService->formatDateTimeToIso($dateTime) : null;
    }
}
