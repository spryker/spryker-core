<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Session\SessionConstants;

$config[ApplicationConstants::YVES_STORAGE_SESSION_TIME_TO_LIVE] = SessionConstants::SESSION_LIFETIME_1_HOUR;

$config[ApplicationConstants::ZED_STORAGE_SESSION_TIME_TO_LIVE] = SessionConstants::SESSION_LIFETIME_30_DAYS;
$config[ApplicationConstants::ZED_SESSION_SAVE_HANDLER] = null;
