<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class PropelMigrationCleaner implements PropelMigrationCleanerInterface
{

    /**
     * @var PropelBaseFolderFinderInterface
     */
    protected $finder;

    /**
     * @param PropelBaseFolderFinderInterface $finder
     */
    public function __construct(PropelBaseFolderFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @inheritDoc
     */
    public function clean()
    {
        $result = false;

        $baseFolders = $this->finder->getBaseFolders();

        if (!empty($baseFolders)) {
            $result = true;

            $fileSystem = new Filesystem();
            foreach ($baseFolders as $folder) {
                $fileSystem->remove($folder);
            }
        }

        return $result;
    }

}
