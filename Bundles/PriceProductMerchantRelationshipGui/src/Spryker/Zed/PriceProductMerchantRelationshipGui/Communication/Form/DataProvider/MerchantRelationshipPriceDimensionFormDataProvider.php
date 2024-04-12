<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form\MerchantRelationshipPriceDimensionForm;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipPriceDimensionFormDataProvider
{
    /**
     * @var int
     */
    protected const PAGE = 1;

    /**
     * @var int
     */
    protected const MAX_PER_PAGE = 100;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $merchantRelationshipChoices = $this->prepareMerchantRelationshipChoices();

        return [
            MerchantRelationshipPriceDimensionForm::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES => $merchantRelationshipChoices,
        ];
    }

    /**
     * @return array<string, array<string, int>>
     */
    protected function prepareMerchantRelationshipChoices(): array
    {
        $choices = [];
        $merchantRelationshipTransfers = $this->getMerchantRelationships();

        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $choices[$merchantRelationshipTransfer->getMerchant()->getName()][$merchantRelationshipTransfer->getName()] = $merchantRelationshipTransfer->getIdMerchantRelationship();
        }

        return $choices;
    }

    /**
     * @return list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    protected function getMerchantRelationships(): array
    {
        $merchantRelationshipTransfers = [];
        $paginationTransfer = (new PaginationTransfer())
            ->setPage(static::PAGE)
            ->setMaxPerPage(static::MAX_PER_PAGE);

        do {
            $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
                ->setPagination($paginationTransfer);

            /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
            $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade
                ->getMerchantRelationshipCollection(null, $merchantRelationshipCriteriaTransfer);

            array_push(
                $merchantRelationshipTransfers,
                ...$merchantRelationshipCollectionTransfer->getMerchantRelationships()->getArrayCopy(),
            );

            $paginationTransfer->setPage($paginationTransfer->getPage() + 1);
        } while ($merchantRelationshipCollectionTransfer->getPaginationOrFail()->getNbResultsOrFail() > count($merchantRelationshipTransfers));

        return $merchantRelationshipTransfers;
    }
}
