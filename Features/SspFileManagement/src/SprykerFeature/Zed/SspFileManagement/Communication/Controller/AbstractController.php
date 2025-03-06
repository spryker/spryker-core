<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface getFileManagerFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 */
abstract class AbstractController extends SprykerAbstractController
{
    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_FILE = 'id-file';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ENTITY_TYPE = 'entity-type';

    /**
     * @var string
     */
    public const REQUEST_PARAM_ENTITY_ID = 'entity-id';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\ViewController::indexAction()
     *
     * @var string
     */
    public const URL_SSP_FILE_MANAGEMENT_VIEW = '/ssp-file-management/view';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\UnlinkController::indexAction()
     *
     * @var string
     */
    public const URL_SSP_FILE_MANAGEMENT_UNLINK = '/ssp-file-management/unlink';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\DeleteController::confirmDeleteAction()
     *
     * @var string
     */
    public const URL_SSP_FILE_MANAGEMENT_CONFIRM_DELETE = '/ssp-file-management/delete/confirm-delete';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\FileAttachController::indexAction()
     *
     * @var string
     */
    public const URL_SSP_FILE_MANAGEMENT_ATTACH = '/ssp-file-management/file-attach';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\AddFilesController::indexAction()
     *
     * @var string
     */
    public const URL_SSP_FILE_MANAGEMENT_ADD_FILE = '/ssp-file-management/add-files';

    /**
     * @uses \Spryker\Zed\FileManagerGui\Communication\Controller\DownloadFileController::indexAction()
     *
     * @var string
     */
    public const URL_FILE_MANAGER_GUI_DOWNLOAD_FILE = '/file-manager-gui/download-file';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_FILE_MANAGEMENT_LIST = '/ssp-file-management/list';

    /**
     * @uses \SprykerFeature\Zed\SspFileManagement\Communication\Controller\DeleteController::indexAction()
     *
     * @var string
     */
    protected const URL_SSP_FILE_MANAGEMENT_DELETE = '/ssp-file-management/delete';

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
