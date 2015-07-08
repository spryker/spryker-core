<?php
/**
 * Created by PhpStorm.
 * User: vsevoloddolgopolov
 * Date: 06/07/15
 * Time: 18:55
 */

namespace SprykerFeature\Zed\Product\Communication\Form;

use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use SprykerFeature\Zed\Product\Communication\Form\Type\AutosuggestType;
use SprykerFeature\Zed\Product\Communication\Form\Type\NoValidateType;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Validator\Validation;

class BuildFormFactoryHelper
{
    protected $tpl;

    public function __construct($tpl)
    {
        $this->tpl = $tpl;
    }

    public function __invoke($app)
    {
        $app['twig.form.templates'] = array_merge(
            [$this->tpl], $app['twig.form.templates']
        );

        $app['twig.form.engine'] = $app->share(function ($app) {
            return new TwigRendererEngine($app['twig.form.templates']);
        });

        $app['twig.form.renderer'] = $app->share(function ($app) {
            return new TwigRenderer($app['twig.form.engine'], $app['form.csrf_provider']);
        });

        $app['twig.loader.filesystem']->addPath(
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/Product/src/SprykerFeature/Zed/Product/Presentation/Product/'
        );


        Locator::getInstance()
            ->application()
            ->pluginPimple()
            ->getApplication()['twig']
            ->addExtension(new FormExtension($app['twig.form.renderer']));
        /* @var $formFactory \Symfony\Component\Form\FormFactory */

        $app['form.types'] = $app->share($app->extend('form.types',
            function ($extensions, $app) {
                return [new AutosuggestType()];
            }));

        $app['form.type.extensions'] = $app->share($app->extend('form.type.extensions',
            function ($extensions, $app) {
                return [
                    new NoValidateType(),
                    new FormTypeValidatorExtension(Validation::createValidator())
                ];
            }));

        return $app['form.factory'];

    }
}
