<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Locale;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Locale\Dependency\External\LocaleToLanguageNegotiatorInterface;
use Spryker\Service\Locale\Mapper\AcceptLanguageMapper;
use Spryker\Service\Locale\Mapper\AcceptLanguageMapperInterface;
use Spryker\Service\Locale\Negotiator\AcceptLanguageNegotiator;
use Spryker\Service\Locale\Negotiator\AcceptLanguageNegotiatorInterface;

class LocaleServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Locale\Negotiator\AcceptLanguageNegotiatorInterface
     */
    public function createAcceptLanguageNegotiator(): AcceptLanguageNegotiatorInterface
    {
        return new AcceptLanguageNegotiator(
            $this->getLanguageNegotiator(),
            $this->createAcceptLanguageMapper(),
        );
    }

    /**
     * @return \Spryker\Service\Locale\Mapper\AcceptLanguageMapperInterface
     */
    public function createAcceptLanguageMapper(): AcceptLanguageMapperInterface
    {
        return new AcceptLanguageMapper();
    }

    /**
     * @return \Spryker\Service\Locale\Dependency\External\LocaleToLanguageNegotiatorInterface
     */
    public function getLanguageNegotiator(): LocaleToLanguageNegotiatorInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::LANGUAGE_NEGOTIATOR);
    }
}
