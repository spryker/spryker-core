<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\GlobalTemplateVariableExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use Symfony\Component\HttpFoundation\Request;

class GlobalTemplateVariablesExtension extends LocatorAwareExtension implements GlobalTemplateVariableExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return array
     */
    public function getGlobalTemplateVariables(Application $app)
    {
        $navigation = $this->getNavigation();
        $breadcrumbs = $navigation['path'];

        return [
            'environment' => APPLICATION_ENV,
            'store' => Store::getInstance()->getStoreName(),
            'title' => Config::get(SystemConfig::PROJECT_NAMESPACE) . ' | Zed | ' . ucfirst(APPLICATION_ENV),
            'currentController' => get_class($this),
            'navigation' => $navigation,
            'breadcrumbs' => $breadcrumbs,
            'username' => $this->getUsername(),
        ];
    }

    /**
     * @return string
     */
    protected function getNavigation()
    {
        $request = Request::createFromGlobals();

        return $this->getLocator()
            ->application()
            ->pluginNavigation()
            ->buildNavigation($request->getPathInfo());
    }

    /**
     * @return string
     */
    protected function getUsername()
    {
        $username = '';

        $userFacade = $this->getLocator()->user()->facade();
        if ($userFacade->hasCurrentUser()) {
            $user = $userFacade->getCurrentUser();
            $username = sprintf('%s %s', $user->getFirstName(), $user->getLastName());
        }

        return $username;
    }

}
