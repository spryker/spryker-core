<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Plugin;

use SprykerFeature\Yves\Twig\Dependency\Plugin\TwigFunctionPluginInterface;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use Twig_SimpleFunction;
use SprykerFeature\Yves\Customer\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class TwigCustomerPlugin extends AbstractPlugin implements TwigFunctionPluginInterface
{

    /**
     * @param Application $application
     *
     * @return array
     */
    public function getFunctions(Application $application)
    {
        $customer = $this->getDependencyContainer()->createCustomer($application);

        return [
            new Twig_SimpleFunction('getUsername', function () use ($customer) {
                return $customer->getUsername();
            }),
            new Twig_SimpleFunction('isLoggedIn', function () use ($customer) {
                return $customer->isLoggedIn();
            }),
        ];
    }

}
