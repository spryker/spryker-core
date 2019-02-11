<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\OAuthFlow $implicit
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\OAuthFlow $password
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\OAuthFlow $clientCredentials
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\OAuthFlow $authorizationCode
 */
class OAuthFlows extends AbstractObject
{
    /**
     * @inheritdoc
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
