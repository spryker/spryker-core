<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use Generated\Shared\Transfer\CommentTransfer;
use SprykerEngine\Shared\Transfer\AbstractTransfer;
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
     * @param $collection
     * @return mixed|void
     */
    public function handleCreateOrUpdate($grid, $collection = null)
    {
        $this->facadeSales->saveComment($collection);
    }

    /**
     * @return CommentTransfer
     */
    protected function loadTransferCollection()
    {
        return new CommentTransfer();
    }

    /**
     * @return CommentTransfer
     */
    protected function loadTransfer()
    {
        return new CommentTransfer();
    }

    /**
     * @param array $parameters
     * @param Request $request
     *
     * @return AbstractTransfer
     */
    protected function createTransfer(array $parameters, Request $request)
    {
        $transfer = parent::createTransfer($parameters, $request);
        $fkSalesOrder = $request->query->get('id_sales_order', null);
        $transfer->setFkSalesOrder($fkSalesOrder);

        return $transfer;
    }


}
