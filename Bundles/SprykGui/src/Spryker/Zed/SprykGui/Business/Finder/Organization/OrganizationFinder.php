<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Organization;

use Generated\Shared\Transfer\OrganizationCollectionTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;

class OrganizationFinder implements OrganizationFinderInterface
{
    /**
     * @var array
     */
    protected $organizationDefinition = [
        'Spryker' => 'spryker/spryker/',
        'SprykerEco' => 'spryker-eco/',
        'SprykerShop' => 'spryker/spryker-shop/',
    ];

    /**
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function findOrganizations(): OrganizationCollectionTransfer
    {
        $organizationCollectionTransfer = new OrganizationCollectionTransfer();

        foreach ($this->organizationDefinition as $organizationName => $subDirectory) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($organizationName)
                ->setRootPath(APPLICATION_ROOT_DIR . '/vendor/' . $subDirectory);

            $organizationCollectionTransfer->addOrganization($organizationTransfer);
        }

        return $organizationCollectionTransfer;
    }
}
