<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Dependency\Mailer;

interface MailToMailerInterface
{
    /**
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject);

    /**
     * @param string $email
     * @param string $name
     *
     * @return void
     */
    public function setFrom($email, $name);

    /**
     * @param string $email
     * @param string $name
     *
     * @return void
     */
    public function addTo($email, $name);

    /**
     * @param string $email
     * @param string|null $name
     *
     * @return void
     */
    public function addBcc(string $email, ?string $name = null): void;

    /**
     * @param string $content
     *
     * @return void
     */
    public function setHtmlContent($content);

    /**
     * @param string $content
     *
     * @return void
     */
    public function setTextContent($content);

    /**
     * @return void
     */
    public function send();

    /**
     * @param string $attachment
     *
     * @return void
     */
    public function addAttachment(string $attachment): void;
}
