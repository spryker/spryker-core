<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Controller;

/**
 * @metho
 */
class NavigationController extends AbstractController
{

    public function prepareNavigationAction()
    {
        $this->getFacade()->prepareNavigation();
        die('<pre><b>' . print_r('DONE', true) . '</b>' . PHP_EOL . __CLASS__ . ' ' . __LINE__);
    }

}
