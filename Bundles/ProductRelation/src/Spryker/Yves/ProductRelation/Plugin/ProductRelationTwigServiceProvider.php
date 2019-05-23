<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Yves\ProductRelation\ProductRelationFactory getFactory()
 */
class ProductRelationTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    use LoggerTrait;

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                $twig->addFunction(
                    $this->createProductRelationFunction($twig)
                );

                return $twig;
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function createProductRelationFunction(Environment $twig)
    {
        $options = ['is_safe' => ['html']];

        return new TwigFunction('product_relation', function ($type, array $params, $name, $templatePath) use ($twig) {

            $productRelationDataProvider = $this->getFactory()
                ->createDataProviderResolver()
                ->resolveByType($type);

            if ($productRelationDataProvider === null) {
                $this->getLogger()->warning(sprintf('Product relation "%s" data provider not found.', $type));

                return '';
            }

            return $twig->render(
                $templatePath,
                [
                    'productRelations' => $productRelationDataProvider->buildTemplateData($params),
                    'name' => $name,
                ]
            );
        }, $options);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}
