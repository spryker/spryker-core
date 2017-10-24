<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Maintenance\Model\Maintenance;

class MaintenanceMarkerFile implements MaintenanceInterface
{
    const MAINTENANCE_FILE = 'maintenance.marker';

    /**
     * @var string
     */
    protected $maintenanceMarkerDirectory;

    /**
     * @var string
     */
    protected $maintenanceMarkerFile;

    /**
     * @param string $maintenanceMarkerDirectory
     * @param string $maintenanceMarkerFile
     */
    public function __construct($maintenanceMarkerDirectory, $maintenanceMarkerFile = self::MAINTENANCE_FILE)
    {
        $this->maintenanceMarkerDirectory = $maintenanceMarkerDirectory;
        $this->maintenanceMarkerFile = $maintenanceMarkerFile;
    }

    /**
     * @return string
     */
    protected function getFilePath()
    {
        return $this->maintenanceMarkerDirectory . '/' . $this->maintenanceMarkerFile;
    }

    /**
     * @return bool
     */
    public function isMaintenanceEnabled()
    {
        return file_exists($this->getFilePath());
    }

    /**
     * @return void
     */
    public function enableMaintenance()
    {
        file_put_contents($this->getFilePath(), '');
    }

    /**
     * @return void
     */
    public function disableMaintenance()
    {
        unlink($this->getFilePath());
    }
}
