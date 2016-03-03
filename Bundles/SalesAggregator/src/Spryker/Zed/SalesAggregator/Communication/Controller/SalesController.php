<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesAggregator\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends AbstractController
{
    /**
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        return [
            'order' => $request->request->get('orderTransfer'),
        ];
    }
}
