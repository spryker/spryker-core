<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Communication;

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
            $glossaryClient = $this->locator->glossary()->client();
            $twigTranslator = $this->factory->createTwigTranslator($glossaryClient);
            $twigTranslator->setLocale($app['locale']);

            return $twigTranslator;
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }

}
