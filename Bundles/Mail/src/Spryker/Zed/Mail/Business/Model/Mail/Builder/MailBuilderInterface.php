<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail\Builder;

use Generated\Shared\Transfer\MailTransfer;

interface MailBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return $this
     */
    public function setMailTransfer(MailTransfer $mailTransfer);

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function getMailTransfer();

    /**
     * @param string $subject
     * @param array $data
     *
     * @return $this
     */
    public function setSubject($subject, array $data = []);

    /**
     * @param string $htmlTemplate
     *
     * @return $this
     */
    public function setHtmlTemplate($htmlTemplate);

    /**
     * @param string $htmlTemplate
     *
     * @return $this
     */
    public function setTextTemplate($htmlTemplate);

    /**
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function setSender($email, $name);

    /**
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addRecipient($email, $name);

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return $this
     */
    public function addRecipientBcc(string $email, ?string $name = null);

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build();
}
