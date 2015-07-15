<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer
 */
class IndexController extends AbstractController
{

    const DATE_FORMAT = 'Y-m-d G:i:s';

    public function indexAction(Request $request)
    {
        return $this->gridAction($request);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function gridAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createCustomerGrid($request);
        $data = $grid->renderData();

        foreach ($data['content']['rows'] as &$value) {
            $value['date_of_birth'] = $this->getFormatedRowDate($value['date_of_birth']);
            $value['registered'] = $this->getFormatedRowDate($value['registered']);
            $value['created_at'] = $this->getFormatedRowDate($value['created_at']);
        }

        return $data;
    }

    /**
     * @param $date
     *
     * @return null|string
     */
    protected function getFormatedRowDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format(self::DATE_FORMAT);
        }

        return;
    }

}
