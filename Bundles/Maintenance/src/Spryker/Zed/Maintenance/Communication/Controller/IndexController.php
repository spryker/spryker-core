<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Maintenance\Communication\MaintenanceCommunicationFactory getFactory()
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'errorLevel' => $this->errorLevelAsString(Config::get(ErrorHandlerConstants::ERROR_LEVEL)),
            'errorLevelLogOnly' => $this->errorLevelAsString(Config::get(ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY, 0)),
        ]);
    }

    /**
     * Turns bitmasked error level into readable string.
     *
     * @param int $value Error level.
     *
     * @return string Errors separated by pipe (|).
     */
    protected function errorLevelAsString($value)
    {
        $levelNames = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        ];

        $levels = [];
        if (($value & E_ALL) === E_ALL) {
            $levels[] = 'E_ALL';
            $value &= ~E_ALL;
        }
        foreach ($levelNames as $level => $name) {
            if (($value & $level) === $level) {
                $levels[] = $name;
            }
        }

        return implode(' | ', $levels);
    }
}
