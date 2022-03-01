<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp;

use Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpander;
use Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpanderInterface;
use Spryker\Glue\GlueHttp\Request\CorsHeaderExistenceValidator;
use Spryker\Glue\GlueHttp\Request\CorsHeaderExistenceValidatorInterface;
use Spryker\Glue\GlueHttp\Request\RequestBuilder;
use Spryker\Glue\GlueHttp\Request\RequestBuilderInterface;
use Spryker\Glue\GlueHttp\Response\HttpSender;
use Spryker\Glue\GlueHttp\Response\HttpSenderInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\GlueHttp\GlueHttpConfig getConfig()
 */
class GlueHttpFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpanderInterface
     */
    public function createGlueContextHttpExpander(): GlueContextHttpExpanderInterface
    {
        return new GlueContextHttpExpander();
    }

    /**
     * @return \Spryker\Glue\GlueHttp\Request\CorsHeaderExistenceValidatorInterface
     */
    public function createCorsHeaderExistenceValidator(): CorsHeaderExistenceValidatorInterface
    {
        return new CorsHeaderExistenceValidator();
    }

    /**
     * @return \Spryker\Glue\GlueHttp\Request\RequestBuilderInterface
     */
    public function createRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder(
            $this->createRequest(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueHttp\Response\HttpSenderInterface;
     */
    public function createHttpSender(): HttpSenderInterface
    {
        return new HttpSender(
            $this->createResponse(),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createResponse(): Response
    {
        return new Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function createRequest(): Request
    {
        return Request::createFromGlobals();
    }
}
