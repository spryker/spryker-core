<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Application\Communication\Controller\AbstractController;

class PhpinfoController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function indexAction(Request $request)
    {
        $what = $request->query->get('what');
        echo $this->facadeSetup->getPhpInfo($what);
    }

}
