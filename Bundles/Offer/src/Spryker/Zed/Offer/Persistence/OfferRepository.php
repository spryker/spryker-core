<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OfferListTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Offer\Persistence\SpyOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * @method \Spryker\Zed\Offer\Persistence\OfferPersistenceFactory getFactory()
 */
class OfferRepository extends AbstractRepository implements OfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    public function getOffers(OfferListTransfer $offerListTransfer): OfferListTransfer
    {
        $offerQuery = $this->getFactory()->createPropelOfferQuery();
        $offerQuery = $this->applyFilterToQuery($offerQuery, $offerListTransfer->getFilter());
        $offerQuery->filterByCustomerReference($offerListTransfer->getCustomerReference());

        $offerQuery = $this->applyPagination($offerQuery, $offerListTransfer->getPagination());

        $offerQuery = $this->buildQueryFromCriteria($offerQuery);
        $offerEntityTransfers = $offerQuery->find();

        $offerListTransfer = $this->hydrateOfferListWithOffers($offerListTransfer, $offerEntityTransfers);

        return $offerListTransfer;
    }

    /**
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getOfferById(int $idOffer): OfferTransfer
    {
        $offerQuery = $this->getFactory()->createPropelOfferQuery();
        $offerQuery->filterByIdOffer($idOffer);

        $offerQuery = $this->buildQueryFromCriteria($offerQuery);
        $offerEntityTransfer = $offerQuery->findOne();

        $offerTransfer = $this->getFactory()
            ->createOfferMapper()
            ->mapOfferEntityToOffer($offerEntityTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Orm\Zed\Offer\Persistence\SpyOfferQuery $spyOfferQuery
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    protected function applyFilterToQuery(SpyOfferQuery $spyOfferQuery, ?FilterTransfer $filterTransfer): SpyOfferQuery
    {
        $criteria = new Criteria();
        if ($filterTransfer !== null) {
            $criteria = (new PropelFilterCriteria($filterTransfer))
                ->toCriteria();
        }

        $spyOfferQuery->mergeWith($criteria);

        return $spyOfferQuery;
    }

    /**
     * @param \Orm\Zed\Offer\Persistence\SpyOfferQuery $spyOfferQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    protected function applyPagination(SpyOfferQuery $spyOfferQuery, PaginationTransfer $paginationTransfer = null): SpyOfferQuery
    {
        if (empty($paginationTransfer)) {
            return $spyOfferQuery;
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $spyOfferQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\OfferListTransfer $offerListTransfer
     * @param array $offerEntityTransfers
     *
     * @return \Generated\Shared\Transfer\OfferListTransfer
     */
    protected function hydrateOfferListWithOffers(
        OfferListTransfer $offerListTransfer,
        array $offerEntityTransfers
    ): OfferListTransfer {
        $offers = new ArrayObject();

        foreach ($offerEntityTransfers as $offerEntityTransfer) {
            $offerTransfer = $this->getFactory()
                ->createOfferMapper()
                ->mapOfferEntityToOffer($offerEntityTransfer);
            $offers->append($offerTransfer);
        }

        return $offerListTransfer->setOffers($offers);
    }
}
