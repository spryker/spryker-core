<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Shared\Library\Application\Version;
/*
 * Class Rev
 * @package SprykerFeature\Zed\Setup\Communication\Controller
 */
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class RevController extends AbstractController{

    public function indexAction()
    {

        $content = Version::getRevTxt();
        echo '<pre>' . $content . '</pre>';
    }

}
