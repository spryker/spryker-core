<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class PhpinfoController extends AbstractController
{

    /**
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $what = $request->query->get('what');
        echo $this->facadeSetup->getPhpInfo($what);
    }

}
