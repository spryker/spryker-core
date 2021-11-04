<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ModuleFinder;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ModuleFinderConfig extends AbstractBundleConfig
{
    /**
     * @var array<string>
     */
    protected $organizationPathFragments = [
        'spryker',
        'spryker-shop',
        'spryker-eco',
        'spryker-sdk',
        'spryker-middleware',
    ];

    /**
     * @api
     *
     * @return array<string>
     */
    public function getInternalOrganizations(): array
    {
        return [
            'Spryker',
            'SprykerShop',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getApplications(): array
    {
        return [
            'Client',
            'Service',
            'Shared',
            'Yves',
            'Zed',
            'Glue',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getInternalPackagePathFragments(): array
    {
        return [
            'spryker',
            'spryker-shop',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPathsToInternalOrganizations(): array
    {
        $organizationPaths = [];
        foreach ($this->organizationPathFragments as $organizationPathFragment) {
            $nonsplitDirectory = sprintf('%s/vendor/spryker/%s/Bundles/', APPLICATION_ROOT_DIR, $organizationPathFragment);
            if (is_dir($nonsplitDirectory)) {
                $organizationPaths[] = $nonsplitDirectory;

                continue;
            }

            $splitDirectory = sprintf('%s/vendor/%s/', APPLICATION_ROOT_DIR, $organizationPathFragment);
            if (is_dir($splitDirectory)) {
                $organizationPaths[] = $splitDirectory;
            }
        }

        return $organizationPaths;
    }
}
