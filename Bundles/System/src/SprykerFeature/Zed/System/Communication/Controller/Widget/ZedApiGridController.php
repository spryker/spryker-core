<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;

class ZedApiGridController extends AbstractGridController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        return $this->viewResponse([
            'grid' => $this->initializeGrid($request),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return mixed|\SprykerFeature_Zed_System_Communication_Grid_ZedApi
     */
    protected function initializeGrid(Request $request)
    {
        $dataSource = new \SprykerFeature_Zed_System_Communication_Grid_ZedApi_DataSource();

        return new \SprykerFeature_Zed_System_Communication_Grid_ZedApi($dataSource);
    }

}
