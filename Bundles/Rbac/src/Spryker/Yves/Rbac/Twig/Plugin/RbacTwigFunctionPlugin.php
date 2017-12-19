<?php

namespace Spryker\Yves\Rbac\Twig\Plugin;

use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Client\Rbac\RbacClientInterface getClient()
 */
class RbacTwigFunctionPlugin extends AbstractPlugin implements TwigFunctionPluginInterface
{
    public function getFunctions(Application $application)
    {
        return [
            new Twig_SimpleFunction('can', [
                $this,
                'can',
            ], [
                'needs_context' => false,
                'needs_environment' => false,
            ]),
        ];
    }

    /**
     * @param string $right
     * @param array $options
     *
     * @return bool
     */
    public function can($right, array $options)
    {
        return $this->getClient()->can($right, $options);
    }
}