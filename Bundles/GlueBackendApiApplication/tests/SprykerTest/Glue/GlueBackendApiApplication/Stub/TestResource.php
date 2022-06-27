<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Stub;

use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeDefinitionPluginInterface;

class TestResource extends AbstractResourcePlugin implements JsonApiResourceInterface, ScopeDefinitionPluginInterface
{
    /**
     * @var string
     */
    protected const RESOURCE_NAME = 'test';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return ResourceController::class;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return (new GlueResourceMethodCollectionTransfer())
            ->setGetCollection(
                (new GlueResourceMethodConfigurationTransfer())
                    ->setAction('getCollectionAction')->setAttributes('\Generated\Shared\Transfer\TestAttributesTransfer'),
            );
    }

    /**
     * @return array<string, string>
     */
    public function getScopes(): array
    {
        return [
            'get' => 'backend:test:read',
        ];
    }
}
