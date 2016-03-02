<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\TwigExtensionInterface;
use Spryker\Shared\Library\Context;
use Spryker\Shared\Library\DateFormatter;
use Spryker\Shared\Library\Twig\DateFormatterTwigExtension;
use Spryker\Zed\Application\Business\Model\Twig\ZedExtension;
use Spryker\Zed\Price\Communication\Plugin\Twig\PriceTwigExtensions;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Translator;

class TwigExtension implements TwigExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $app
     *
     * @return \Twig_Extension[]
     */
    public function getTwigExtensions(Application $app)
    {
        return [
            new ZedExtension(),
            new TranslationExtension(new Translator($app['locale'])),
            new PriceTwigExtensions(),
            new DateFormatterTwigExtension(new DateFormatter(Context::getInstance())),
        ];
    }

}
