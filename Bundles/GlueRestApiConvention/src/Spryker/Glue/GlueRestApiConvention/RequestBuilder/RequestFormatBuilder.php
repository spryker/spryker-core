<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;

class RequestFormatBuilder implements RequestBuilderInterface
{
    /**
     * @var array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    protected array $responseEncoderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface> $responseEncoderPlugins
     */
    public function __construct(array $responseEncoderPlugins)
    {
        $this->responseEncoderPlugins = $responseEncoderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $headers = $glueRequestTransfer->getMeta();
        if (isset($headers['content-type'])) {
            $glueRequestTransfer->setRequestedFormat($headers['content-type'][0]);
        }
        if (isset($headers['accept'])) {
            $acceptedFormatsFromRequest = $this->getAcceptedFormat($headers['accept'][0]);

            if (!$acceptedFormatsFromRequest) {
                $glueRequestTransfer->setAcceptedFormat(
                    $this->responseEncoderPlugins[0]->getAcceptedFormats()[0],
                );
            }

            foreach ($this->responseEncoderPlugins as $responseEncoderPlugin) {
                if (array_intersect($acceptedFormatsFromRequest, $responseEncoderPlugin->getAcceptedFormats())) {
                    $glueRequestTransfer->setAcceptedFormat($responseEncoderPlugin->getAcceptedFormats()[0]);
                }
            }
        }

        return $glueRequestTransfer;
    }

    /**
     * @param string $acceptHeaders
     *
     * @return array<string>
     */
    protected function getAcceptedFormat(string $acceptHeaders): array
    {
        $splitAcceptHeaderValues = explode(',', $acceptHeaders);

        return array_map('trim', $splitAcceptHeaderValues);
    }
}
