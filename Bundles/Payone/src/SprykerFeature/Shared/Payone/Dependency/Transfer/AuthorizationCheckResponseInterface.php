<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;


interface AuthorizationCheckResponseInterface
{

    /**
     * @param bool $isSuccess
     */
    public function setIsSuccess($isSuccess);

    /**
     * @return bool
     */
    public function getIsSuccess();

    /**
     * @param string $request
     */
    public function setRequest($request);

    /**
     * @return string
     */
    public function getRequest();

    /**
     * @param string $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl);

    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @param bool $isRedirect
     */
    public function setIsRedirect($isRedirect);

    /**
     * @return bool
     */
    public function getIsRedirect();

    /**
     * @param string $message
     */
    public function setInternalErrorMessage($message);

    /**
     * @return string
     */
    public function getInternalErrorMessage();

    /**
     * @param string $message
     */
    public function setCustomerErrorMessage($message);

    /**
     * @return string
     */
    public function getCustomerErrorMessage();

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode);

    /**
     * @return string
     */
    public function getErrorCode();

}