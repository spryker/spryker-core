<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class TranslationServiceProvider extends \Silex\Provider\TranslationServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $currentLanguage = \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLanguage();
        $pathToLanguageFile = APPLICATION_ROOT_DIR . '/config/Zed/language/' . $currentLanguage . '/lang.csv';

        $translator = new \SprykerFeature_Zed_Library_Translate(
            [
                'adapter' => 'csv',
                'content' => $pathToLanguageFile,
                'locale' => \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale(),
            ]
        );
        \Zend_Registry::set('Zend_Translate', $translator);
    }

}
