<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DiscontinueProductForm;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface;

class DiscontinueProductFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade\ProductDiscontinuedGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductDiscontinuedGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function getData(int $idProductConcrete): array
    {
        $data = [];
        $productDiscontinuedTransfer = $this->findProductDiscontinuedByProductId($idProductConcrete);
        $data[ProductConcreteTransfer::PRODUCT_DISCONTINUED] = $productDiscontinuedTransfer;
        if (!$productDiscontinuedTransfer) {
            return $data;
        }
        $data[DiscontinueProductForm::FIELD_DISCONTINUED_NOTES] = $this->getLocalizedNotes($productDiscontinuedTransfer);

        return $data;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    protected function findProductDiscontinuedByProductId(int $idProductConcrete): ?ProductDiscontinuedTransfer
    {
        return $this->productDiscontinuedFacade->findProductDiscontinuedByProductId($idProductConcrete)
            ->getProductDiscontinued();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer>
     */
    protected function getLocalizedNotes(ProductDiscontinuedTransfer $productDiscontinuedTransfer): array
    {
        $indexedProductDiscontinuedNoteTransfers = $this->indexProductDiscontinuedNotes((array)$productDiscontinuedTransfer->getProductDiscontinuedNotes());
        foreach ($this->localeFacade->getAvailableLocales() as $localeName) {
            $idLocale = $this->localeFacade->getLocale($localeName)->getIdLocale();
            if (isset($indexedProductDiscontinuedNoteTransfers[$idLocale])) {
                continue;
            }
            $indexedProductDiscontinuedNoteTransfers[$idLocale] = (new ProductDiscontinuedNoteTransfer())
                ->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
                ->setFkLocale($idLocale);
        }

        return $indexedProductDiscontinuedNoteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer> $productDiscontinuedNoteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer>
     */
    protected function indexProductDiscontinuedNotes(array $productDiscontinuedNoteTransfers): array
    {
        $indexedProductDiscontinuedNoteTransfers = [];
        foreach ($productDiscontinuedNoteTransfers as $productDiscontinuedNoteTransfer) {
            $indexedProductDiscontinuedNoteTransfers[$productDiscontinuedNoteTransfer->getFkLocale()] = $productDiscontinuedNoteTransfer;
        }

        return $indexedProductDiscontinuedNoteTransfers;
    }
}
