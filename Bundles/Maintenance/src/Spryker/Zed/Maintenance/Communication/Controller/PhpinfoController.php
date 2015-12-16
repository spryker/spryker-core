<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Maintenance\Business\MaintenanceFacade;
use Spryker\Zed\Maintenance\Communication\MaintenanceDependencyContainer;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getCommunicationFactory()
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
