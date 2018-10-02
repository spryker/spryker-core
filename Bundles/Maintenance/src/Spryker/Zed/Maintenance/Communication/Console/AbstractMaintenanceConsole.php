<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Maintenance\Business\Exception\InvalidApplicationNameException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacadeInterface getFacade()
 */
abstract class AbstractMaintenanceConsole extends Console
{
    public const ARGUMENT_APPLICATION = 'application';

    public const APPLICATION_ALL = 'all';
    public const APPLICATION_YVES = 'yves';
    public const APPLICATION_ZED = 'zed';

    /**
     * @var array
     */
    protected $allowedApplications = [
        self::APPLICATION_YVES,
        self::APPLICATION_ZED,
        self::APPLICATION_ALL,
    ];

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \Spryker\Zed\Maintenance\Business\Exception\InvalidApplicationNameException
     *
     * @return string
     */
    protected function getApplicationName(InputInterface $input)
    {
        $applicationName = strtolower($this->getInputString($input, static::ARGUMENT_APPLICATION));

        if (!in_array($applicationName, $this->allowedApplications)) {
            throw new InvalidApplicationNameException(sprintf('Invalid application name. Given "%s" only on of "%s" is allowed.', $applicationName, implode(', ', $this->allowedApplications)));
        }

        return $applicationName;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $argumentName
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function getInputString(InputInterface $input, string $argumentName): string
    {
        $inputString = $input->getArgument($argumentName);

        if (!is_string($inputString)) {
            throw new InvalidArgumentException('Invalid input type, string expected');
        }

        return $inputString;
    }
}
