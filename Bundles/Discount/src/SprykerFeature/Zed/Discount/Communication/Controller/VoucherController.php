<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method VoucherDependencyContainer getDependencyContainer()
 */
class VoucherController extends AbstractController
{

    public function createAction()
    {
        $form = $this->getDependencyContainer()->createFormVoucherForm();
        $form->handleRequest();

        return [
            'form' => $form->createView(),
        ];

    }

    public function indexAction()
    {
    }

    public function categoryAction()
    {
    }

    public function poolAction()
    {
    }

}
