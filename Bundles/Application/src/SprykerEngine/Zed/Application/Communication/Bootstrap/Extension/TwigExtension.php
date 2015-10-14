<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\TwigExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerFeature\Shared\Library\Context;
use SprykerFeature\Shared\Library\DateFormatter;
use SprykerFeature\Shared\Library\Twig\DateFormatterTwigExtension;
use SprykerFeature\Zed\Application\Business\Model\Twig\ZedExtension;
use SprykerFeature\Zed\Price\Communication\Plugin\Twig\PriceTwigExtensions;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class TwigExtension implements TwigExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return \Twig_Extension[]
     */
    public function getTwigExtensions(Application $app)
    {
        return [
            new ZedExtension(),
            new TranslationExtension($app['translator']),
            new PriceTwigExtensions(),
            new DateFormatterTwigExtension(new DateFormatter(Context::getInstance())),
        ];
    }

}
