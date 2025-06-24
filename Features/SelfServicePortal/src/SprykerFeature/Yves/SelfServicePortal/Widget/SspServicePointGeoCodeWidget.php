<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ServicePointSearchTransfer;
use LogicException;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspServicePointGeoCodeWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_LATITUDE = 'latitude';

    /**
     * @var string
     */
    protected const PARAMETER_LONGITUDE = 'longitude';

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     */
    public function __construct(ServicePointSearchTransfer $servicePointSearchTransfer)
    {
        $this->addLatitudeParameter($servicePointSearchTransfer);
        $this->addLongitudeParameter($servicePointSearchTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServicePointGeoCodeWidget';
    }

    /**
     * @throws \LogicException
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        throw new LogicException('This widget should only be used as geo coordinates provider.');
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     *
     * @return void
     */
    protected function addLatitudeParameter(ServicePointSearchTransfer $servicePointSearchTransfer): void
    {
        $latitude = null;

        if ($servicePointSearchTransfer->getAddress() && $servicePointSearchTransfer->getAddressOrFail()->getLatitude()) {
            $latitude = $servicePointSearchTransfer->getAddressOrFail()->getLatitude();
        }

        $this->addParameter(static::PARAMETER_LATITUDE, $latitude);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     *
     * @return void
     */
    protected function addLongitudeParameter(ServicePointSearchTransfer $servicePointSearchTransfer): void
    {
        $longitude = null;

        if ($servicePointSearchTransfer->getAddress() && $servicePointSearchTransfer->getAddressOrFail()->getLongitude()) {
            $longitude = $servicePointSearchTransfer->getAddressOrFail()->getLongitude();
        }

        $this->addParameter(static::PARAMETER_LONGITUDE, $longitude);
    }
}
