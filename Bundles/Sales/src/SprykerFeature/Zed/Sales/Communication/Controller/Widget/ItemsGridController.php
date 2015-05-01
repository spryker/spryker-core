<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Sales\Communication\Grid\Items as ItemsGrid;
use SprykerFeature\Zed\Sales\Communication\Grid\Items\DataSource;

class ItemsGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|ItemsGrid
     */
    protected function initializeGrid(Request $request)
    {
        $processId = $request->query->get('process');
        $statusId = $request->query->get('status');
        $age = $request->query->get('age');

        $itemsDataSource = new DataSource();
        $itemsDataSource->setAdditionalParams($processId, $statusId, $age);

        return new ItemsGrid($itemsDataSource);
    }


}
