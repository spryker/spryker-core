<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\InstalledPackages;

use Generated\Shared\Maintenance\InstalledPackagesInterface;

class MarkDownWriter implements MarkDownWriterInterface
{

    /**
     * @var InstalledPackagesInterface
     */
    private $installedPackages;

    /**
     * @var string
     */
    private $path;

    /**
     * @param InstalledPackagesInterface $installedPackages
     * @param string $path
     */
    public function __construct(InstalledPackagesInterface $installedPackages, $path)
    {
        $this->installedPackages = $installedPackages;
        $this->path = $path;
    }

    public function write()
    {
        $markDownLines = [];
        $markDownLines[] = '|Name|Version|License|Url|Type|';
        $markDownLines[] = '|----|-------|-------|---|----|';

        foreach ($this->installedPackages->getPackages() as $package) {
            $markDownLines[] = '|' . $package->getName() . '|' . $package->getVersion() . '|' . implode(', ', (array) $package->getLicense()) . '|' . $package->getUrl() . '|' . $package->getType() . '|';
        }

        file_put_contents($this->path, implode("\n", $markDownLines));
    }

}
