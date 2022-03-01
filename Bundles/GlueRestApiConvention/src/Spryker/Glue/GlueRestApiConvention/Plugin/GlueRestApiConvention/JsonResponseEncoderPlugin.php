<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionFactory getFactory()
 */
class JsonResponseEncoderPlugin extends AbstractPlugin implements ResponseEncoderPluginInterface
{
    /**
     * @var string
     */
    protected const ACCEPTED_CONTENT_TYPE = 'application/json';

    /**
     * {@inheritDoc}
     * - Return all formats that mean the JSON encoder can be used.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAcceptedFormats(): array
    {
        return [static::ACCEPTED_CONTENT_TYPE];
    }

    /**
     * {@inheritDoc}
     * - Check if the given content can be encoded by this implementation.
     * - Always returns true, is the default encoder.
     *
     * @api
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function accepts($content, GlueRequestTransfer $glueRequestTransfer): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     * - Transforms given content into JSON format and sets to the `GlueResponseTransfer.content`.
     * - Sets `Content-Type=application/json` to the `GlueResponseTransfer.meta`.
     *
     * @api
     *
     * @param mixed $content
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function encode($content, GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer->addMeta('Content-Type', static::ACCEPTED_CONTENT_TYPE);
        $glueResponseTransfer->setContent($this->getFactory()->getUtilEncodingService()->encodeJson($content));

        return $glueResponseTransfer;
    }
}
