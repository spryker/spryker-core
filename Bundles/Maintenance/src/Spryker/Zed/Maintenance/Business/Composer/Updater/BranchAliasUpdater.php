<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class BranchAliasUpdater implements UpdaterInterface
{

    const KEY_EXTRA = 'extra';
    const KEY_BRANCH_ALIAS = 'branch-alias';
    const KEY_MASTER_BRANCH = 'dev-master';

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        $composerJson[self::KEY_EXTRA] = [
          self::KEY_BRANCH_ALIAS => [
              self::KEY_MASTER_BRANCH => $this->version,
          ],
        ];

        return $composerJson;
    }

}
