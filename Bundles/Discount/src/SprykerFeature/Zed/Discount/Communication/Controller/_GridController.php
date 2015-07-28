<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function discountAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getDiscountGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function decisionRuleAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getDecisionRuleGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getVoucherGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherPoolCategoryAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getVoucherPoolCategoryGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function voucherPoolAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->getVoucherPoolGrid($request);

        return $this->jsonResponse($grid->toArray());
    }

}
