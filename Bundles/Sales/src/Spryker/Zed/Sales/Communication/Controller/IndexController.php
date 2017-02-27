<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacade getFacade()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $result = (new \Spryker\Zed\DataFeed\Business\DataFeedFacade())
            ->getPriceDataFeed(
                (new \Generated\Shared\Transfer\DataFeedConditionTransfer())
                    ->setLocale((new \Generated\Shared\Transfer\LocaleTransfer())->setIdLocale(46))
                    ->setPagination((new \Generated\Shared\Transfer\DataFeedPaginationTransfer())->setLimit(20)->setOffset(0))
                    ->setDateFilter((new \Generated\Shared\Transfer\DataFeedDateFilterTransfer()))
                    ->setProductFeedJoin((new \Generated\Shared\Transfer\ProductFeedJoinTransfer())
                        ->setIsJoinCategory(1)
                        ->setIsJoinImage(1)
                        ->setIsJoinOption(1)
                        ->setIsJoinPrice(1)
                        ->setIsJoinVariant(1)
                    )
                    ->setPriceFeedJoin((new \Generated\Shared\Transfer\PriceFeedJoinTransfer()))
                    ->setStockFeedJoin((new \Generated\Shared\Transfer\StockFeedJoinTransfer()))
                    ->setCategoryFeedJoin((new \Generated\Shared\Transfer\CategoryFeedJoinTransfer()))
            );
        var_dump(\GuzzleHttp\json_encode($result));
        die;

        $table = $this->getFactory()->createOrdersTable();

        return [
            'orders' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createOrdersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
