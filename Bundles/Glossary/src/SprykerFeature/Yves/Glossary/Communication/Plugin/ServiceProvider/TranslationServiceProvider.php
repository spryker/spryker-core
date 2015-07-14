<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Communication\Plugin\ServiceProvider;

use Generated\Yves\Ide\AutoCompletion;
use Generated\Yves\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Client\Glossary\Service\GlossaryClientInterface;

/**
 * @method GlossaryCommunication getFactory()
 */
class TranslationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{


    private $glossaryClient;

    /**
     * @param GlossaryClientInterface $glossaryClient
     *
     * @return $this
     */
    public function setGlossaryClient(GlossaryClientInterface $glossaryClient)
    {
        $this->glossaryClient = $glossaryClient;

        return $this;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['translator'] = $app->share(function ($app) {
            $twigTranslator = $this->getFactory()->createTwigTranslator(
                $this->glossaryClient, $app['locale']
            );

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
