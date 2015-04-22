<?php

namespace SprykerFeature\Yves\Glossary;

use Generated\Yves\Ide\AutoCompletion;
use Generated\Yves\Ide\FactoryAutoCompletion\Glossary;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;

class TranslationServiceProvider implements ServiceProviderInterface
{

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var FactoryInterface|Glossary
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(FactoryInterface $factory, LocatorLocatorInterface $locator)
    {
        $this->factory = $factory;
        $this->locator = $locator;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['translator'] = $app->share(function ($app) {
            $translator = $this->locator->glossary()->sdk()->createTranslator($app['locale']);
            $returned = $this->factory->createTwigTranslator($translator);

            return $returned;
        });
    }

    public function boot(Application $app)
    {
    }
}
