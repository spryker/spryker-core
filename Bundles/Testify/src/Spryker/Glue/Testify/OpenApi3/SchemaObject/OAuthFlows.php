<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\OAuthFlow $implicit
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\OAuthFlow $password
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\OAuthFlow $clientCredentials
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\OAuthFlow $authorizationCode
 */
class OAuthFlows extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('implicit', new PropertyDefinition(OAuthFlow::class))
            ->setProperty('password', new PropertyDefinition(OAuthFlow::class))
            ->setProperty('clientCredentials', new PropertyDefinition(OAuthFlow::class))
            ->setProperty('authorizationCode', new PropertyDefinition(OAuthFlow::class));
    }
}
