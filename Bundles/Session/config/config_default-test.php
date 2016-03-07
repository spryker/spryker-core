<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Session\SessionConstants;

$config[ApplicationConstants::YVES_STORAGE_SESSION_TIME_TO_LIVE] = SessionConstants::SESSION_LIFETIME_1_HOUR;
$config[ApplicationConstants::YVES_STORAGE_SESSION_FILE_PATH] = session_save_path();

$config[ApplicationConstants::ZED_STORAGE_SESSION_TIME_TO_LIVE] = SessionConstants::SESSION_LIFETIME_30_DAYS;
$config[ApplicationConstants::ZED_STORAGE_SESSION_COOKIE_NAME] = 'zed_session';
$config[ApplicationConstants::ZED_STORAGE_SESSION_FILE_PATH] = session_save_path();
$config[ApplicationConstants::ZED_SESSION_SAVE_HANDLER] = null;
