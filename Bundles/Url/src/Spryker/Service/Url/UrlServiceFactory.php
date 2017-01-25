<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Url\Builder\UrlBuilder;
use Spryker\Service\Url\Generator\UrlGenerator;
use Spryker\Service\Url\Parser\UrlParser;

class UrlServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\Url\Parser\UrlParserInterface
     */
    public function createUrlParser()
    {
        return new UrlParser();
    }

    /**
     * @return \Spryker\Service\Url\Generator\UrlGeneratorInterface
     */
    public function createUrlGenerator()
    {
        return new UrlGenerator();
    }

    /**
     * @return \Spryker\Service\Url\Builder\UrlBuilderInterface
     */
    public function createUrlBuilder()
    {
        return new UrlBuilder();
    }

}
