<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Communication\Plugin;

use SprykerFeature\Yves\Twig\Communication\Dependency\Plugin\TwigFunctionPluginInterface;
use Silex\Application;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use Twig_SimpleFunction;
use SprykerFeature\Yves\Customer\Communication\CustomerDependencyContainer;

/**
 * @method CustomerDependencyContainer getDependencyContainer()
 */
class TwigCustomer extends AbstractPlugin implements TwigFunctionPluginInterface
{

    /**
     * @param Application $application
     *
     * @return array
     */
    public function getFunctions(Application $application)
    {
        return [
            new Twig_SimpleFunction('getUsername', function () use ($application) {
                $customer = $this->getDependencyContainer()->createCustomer($application);

                return $customer->getUsername();
            }),
            new Twig_SimpleFunction('isLoggedIn', function () use ($application) {
                $customer = $this->getDependencyContainer()->createCustomer($application);

                return $customer->isLoggedIn();
            }),
        ];
    }

}
