<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Grid\Note\DataSource;
use SprykerFeature\Zed\Sales\Communication\Grid\Note as NoteGrid;

class NoteGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|NoteGrid
     */
    protected function initializeGrid(Request $request)
    {
        return new NoteGrid(new DataSource($request->query->get('id_sales_order')));
    }


}
