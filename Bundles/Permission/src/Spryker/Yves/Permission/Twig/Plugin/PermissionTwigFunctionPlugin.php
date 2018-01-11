<?php

namespace Spryker\Yves\Permission\Twig\Plugin;

use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;

/**
 * @method \Spryker\Client\Permission\PermissionClientInterface getClient()
 */
class PermissionTwigFunctionPlugin extends AbstractPlugin implements TwigFunctionPluginInterface
{
    /**
     * @example for a twig template
     * {{if (can('permission.allow.product.price'))}}
     *      {% productAbstract.price %}
     * {{ endif }}
     *
     * @param Application $application
     * @return array
     */
    public function getFunctions(Application $application)
    {
        return [
            new \Twig_SimpleFunction('can', [
                $this,
                'can',
            ], [
                'needs_context' => false,
                'needs_environment' => false,
            ]),
        ];
    }

    /**
     * @param string $permissionKey
     * @param array $options
     *
     * @return bool
     */
    public function can($permissionKey, array $options)
    {
        return $this->getClient()->can($permissionKey, $options);
    }
}