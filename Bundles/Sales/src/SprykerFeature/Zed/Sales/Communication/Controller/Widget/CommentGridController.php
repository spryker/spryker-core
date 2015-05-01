<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection;
use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Grid\Comment as CommentGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\Comment\DataSource;

class CommentGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|CommentGrid
     */
    protected function initializeGrid(Request $request)
    {
        return new CommentGrid(new DataSource($request->query->get('id_sales_order')));
    }

    /**
     * @param $grid
     * @param AbstractTransferCollection $collection
     * @return mixed|void
     */
    public function handleCreateOrUpdate($grid, \SprykerFeature\Shared\Library\TransferObject\AbstractTransferCollection $collection = null)
    {
        $this->facadeSales->saveComment($collection);
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\CommentCollection
     */
    protected function loadTransferCollection()
    {
        return new \Generated\Shared\Transfer\SalesCommentTransfer();
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Comment
     */
    protected function loadTransfer()
    {
        return new \Generated\Shared\Transfer\SalesCommentTransfer();
    }

    /**
     * @param array $parameters
     * @param Request $request
     * @return \SprykerFeature\Shared\Sales\Transfer\Comment|\SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
     */
    protected function createTransfer(array $parameters, Request $request)
    {
        /* @var $transfer \SprykerFeature\Shared\Sales\Transfer\Comment */
        $transfer = parent::createTransfer($parameters, $request);
        $fkSalesOrder = $request->query->get('id_sales_order', null);
        $transfer->setFkSalesOrder($fkSalesOrder);

        return $transfer;
    }


}
