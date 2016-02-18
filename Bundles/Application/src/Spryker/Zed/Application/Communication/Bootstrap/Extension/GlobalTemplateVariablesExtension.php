<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Zed\Application\Communication\Plugin\Navigation;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\GlobalTemplateVariableExtensionInterface;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Symfony\Component\HttpFoundation\Request;

class GlobalTemplateVariablesExtension extends LocatorAwareExtension implements GlobalTemplateVariableExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $app
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
            'title' => Config::get(ApplicationConstants::PROJECT_NAMESPACE) . ' | Zed | ' . ucfirst(APPLICATION_ENV),
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

        return (new Navigation())
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
