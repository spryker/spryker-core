<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\Communication\MaintenanceDependencyContainer;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class PhpinfoController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $phpInfoContent = $this->getPhpInfoHtml();

        return $this->viewResponse([
            'phpinfo' => $phpInfoContent,
        ]);
    }

    /**
     * @return string
     */
    protected function getPhpInfoHtml()
    {
        ob_start();
        phpinfo();
        $phpInfo = ob_get_clean();

        preg_match("/<body[^>]*>(.*?)<\/body>/is", $phpInfo, $matches);

        $phpInfoContent = $matches[1];
        $phpInfoContent = str_replace('div class="center"', 'div class="phpinfo-content"', $phpInfoContent);

        return $this->removePhpLicenceInfo($phpInfoContent);
    }

    /**
     * @param string $phpInfoContent
     *
     * @return string
     */
    protected function removePhpLicenceInfo($phpInfoContent)
    {
        $maxStringLengthNeeded = strpos($phpInfoContent, '<h2>PHP License</h2>');

        $phpInfoContent = substr($phpInfoContent, 0, $maxStringLengthNeeded);

        return $phpInfoContent;
    }

}
