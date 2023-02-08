<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Plugin\GlueJsonApiConvention;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\UsersBackendApi\UsersBackendApiConfig;

/**
 * @method \Spryker\Glue\UsersBackendApi\UsersBackendApiFactory getFactory()
 */
class UserByWarehouseUserAssignmentBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds relationship between `warehouse-user-assignments` and `users` resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addRelationships(array $resources, GlueRequestTransfer $glueRequestTransfer): void
    {
        $this->getFactory()
            ->createUserByWarehouseUserAssignmentResourceRelationshipExpander()
            ->expandWarehouseUserAssignmentsResourcesWithUsersResourceRelationships($resources);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return UsersBackendApiConfig::RESOURCE_TYPE_USERS;
    }
}
