<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
abstract class FileAbstractController extends SprykerAbstractController
{
    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_FILE = 'id-file';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_FILE_INFO = 'id-file-info';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ENTITY_TYPE = 'entity-type';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ENTITY_ID = 'entity-id';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewFileController::indexAction()
     *
     * @var string
     */
    public const URL_PATH_VIEW_FILE = '/self-service-portal/view-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\UnlinkFileController::indexAction()
     *
     * @var string
     */
    public const URL_PATH_UNLINK_FILE = '/self-service-portal/unlink-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\DeleteFileController::confirmDeleteAction()
     *
     * @var string
     */
    public const URL_PATH_DELETE_FILE_CONFIRM_DELETE = '/self-service-portal/delete-file/confirm-delete';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\DeleteFileController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_DELETE_FILE = '/self-service-portal/delete-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\AttachFileController::indexAction()
     *
     * @var string
     */
    public const URL_PATH_ATTACH_FILE = '/self-service-portal/attach-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\AddFileController::indexAction()
     *
     * @var string
     */
    public const URL_PATH_ADD_FILE = '/self-service-portal/add-file';

    /**
     * @uses \Spryker\Zed\FileManagerGui\Communication\Controller\DownloadFileController::indexAction()
     *
     * @var string
     */
    public const URL_PATH_FILE_MANAGER_GUI_DOWNLOAD_FILE = '/file-manager-gui/download-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListFileController::indexAction()
     *
     * @var string
     */
    protected const URL_PATH_LIST_FILE = '/self-service-portal/list-file';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_DOES_NOT_EXIST = 'File with ID "%id%" does not exist.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID = '%id%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CSRF_TOKEN_INVALID = 'CSRF token is not valid.';
}
