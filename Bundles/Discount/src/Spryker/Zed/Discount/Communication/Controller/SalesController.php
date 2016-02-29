<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
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
