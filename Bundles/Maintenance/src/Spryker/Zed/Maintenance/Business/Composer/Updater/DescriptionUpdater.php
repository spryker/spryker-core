<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

class DescriptionUpdater implements UpdaterInterface
{

    const KEY_DESCRIPTION = 'description';

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        $composerJson[self::KEY_DESCRIPTION] = $this->getBundleName($composerJson) . ' bundle';

        return $composerJson;
    }

    /**
     * @param array $composerJsonData
     *
     * @return string
     */
    private function getBundleName($composerJsonData)
    {
        $nameParts = explode('/', $composerJsonData['name']);
        $bundleName = array_pop($nameParts);
        return str_replace('-', ' ', $bundleName);
    }

}
