<?php

namespace SprykerFeature\Shared\Mail\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class MailTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var array
     */
    protected $templateContent = [];

    /**
     * @var bool
     */
    protected $async = false;

    /**
     * @var string
     */
    protected $ipPool;

    /**
     * @var \DateTime
     */
    protected $sendAt = null;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @var string
     */
    protected $fromName;

    /**
     * @var array
     */
    protected $recipients = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var bool
     */
    protected $important = false;

    /**
     * @var bool
     */
    protected $trackOpens = false;

    /**
     * @var bool
     */
    protected $trackClicks = false;

    /**
     * @var bool
     */
    protected $autoText = false;

    /**
     * @var bool
     */
    protected $autoHtml = false;

    /**
     * @var bool
     */
    protected $inlineCss = false;

    /**
     * @var bool
     */
    protected $urlStripQueryString = false;

    /**
     * @var bool
     */
    protected $preserveRecipients = false;

    /**
     * @var bool
     */
    protected $viewContentLink = false;

    /**
     * @var string
     */
    protected $bccAddress;

    /**
     * @var string
     */
    protected $trackingDomain;

    /**
     * @var string
     */
    protected $signingDomain;

    /**
     * @var string
     */
    protected $returnPathDomain;

    /**
     * @var bool
     */
    protected $merge = false;

    /**
     * @var string
     */
    protected $mergeLanguage = 'mailchimp';

    /**
     * @var array
     */
    protected $globalMergeVars = [];

    /**
     * @var array
     */
    protected $recipientMergeVars = [];

    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var string
     */
    protected $subAccount;

    /**
     * @var array
     */
    protected $googleAnalyticsDomains = [];

    /**
     * @var string
     */
    protected $googleAnalyticsCampaign;

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * @var array
     */
    protected $recipientMetadata = [];

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @var array
     */
    protected $images = [];

    /**
     * @param string $templateName
     *
     * @return $this
     */
    public function setTemplateName($templateName)
    {
        $this->addModifiedProperty('templateName');
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @return array
     */
    public function getTemplateContent()
    {
        return $this->templateContent;
    }

    /**
     * @param array $templateContent
     *
     * @return $this
     */
    public function setTemplateContent($templateContent)
    {
        $this->addModifiedProperty('templateContent');
        $this->templateContent = $templateContent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAsync()
    {
        return $this->async;
    }

    /**
     * @param bool $async
     *
     * @return $this
     */
    public function setAsync($async)
    {
        $this->addModifiedProperty('async');
        $this->async = $async;

        return $this;
    }

    /**
     * @return string
     */
    public function getIpPool()
    {
        return $this->ipPool;
    }

    /**
     * @param string $ipPool
     *
     * @return $this
     */
    public function setIpPool($ipPool)
    {
        $this->addModifiedProperty('ipPool');
        $this->ipPool = $ipPool;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * @param \DateTime $sendAt
     *
     * @return $this
     */
    public function setSendAt(\DateTime $sendAt)
    {
        $this->addModifiedProperty('sendAt');
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->addModifiedProperty('subject');
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     *
     * @return $this
     */
    public function setFromEmail($fromEmail)
    {
        $this->addModifiedProperty('fromEmail');
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->addModifiedProperty('fromName');
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param string $toMail
     * @param string $toName
     * @param string $toType
     *
     * @return $this
     */
    public function addRecipient($toMail, $toName = null, $toType = 'to')
    {
        $this->addModifiedProperty('recipients');
        $this->recipients[] = [
            'email' => $toMail,
            'name' => $toName,
            'type' => $toType
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $headerName
     * @param string $headerValue
     *
     * @return $this
     */
    public function addHeader($headerName, $headerValue)
    {
        $this->addModifiedProperty('headers');
        $this->headers[$headerName] = $headerValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isImportant()
    {
        return $this->important;
    }

    /**
     * @param bool $important
     *
     * @return $this
     */
    public function setImportant($important)
    {
        $this->addModifiedProperty('important');
        $this->important = $important;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTrackOpens()
    {
        return $this->trackOpens;
    }

    /**
     * @param bool $trackOpens
     *
     * @return $this
     */
    public function setTrackOpens($trackOpens)
    {
        $this->addModifiedProperty('trackOpens');
        $this->trackOpens = $trackOpens;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTrackClicks()
    {
        return $this->trackClicks;
    }

    /**
     * @param bool $trackClicks
     *
     * @return $this
     */
    public function setTrackClicks($trackClicks)
    {
        $this->addModifiedProperty('trackClicks');
        $this->trackClicks = $trackClicks;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoText()
    {
        return $this->autoText;
    }

    /**
     * @param bool $autoText
     *
     * @return $this
     */
    public function setAutoText($autoText)
    {
        $this->addModifiedProperty('autoText');
        $this->autoText = $autoText;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoHtml()
    {
        return $this->autoHtml;
    }

    /**
     * @param bool $autoHtml
     *
     * @return $this
     */
    public function setAutoHtml($autoHtml)
    {
        $this->addModifiedProperty('autoHtml');
        $this->autoHtml = $autoHtml;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInlineCss()
    {
        return $this->inlineCss;
    }

    /**
     * @param bool $inlineCss
     *
     * @return $this
     */
    public function setInlineCss($inlineCss)
    {
        $this->addModifiedProperty('inlineCss');
        $this->inlineCss = $inlineCss;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUrlStripQueryString()
    {
        return $this->urlStripQueryString;
    }

    /**
     * @param bool $urlStripQueryString
     *
     * @return $this
     */
    public function setUrlStripQueryString($urlStripQueryString)
    {
        $this->addModifiedProperty('urlStripQueryString');
        $this->urlStripQueryString = $urlStripQueryString;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPreserveRecipients()
    {
        return $this->preserveRecipients;
    }

    /**
     * @param bool $preserveRecipients
     *
     * @return $this
     */
    public function setPreserveRecipients($preserveRecipients)
    {
        $this->addModifiedProperty('preserveRecipients');
        $this->preserveRecipients = $preserveRecipients;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewContentLink()
    {
        return $this->viewContentLink;
    }

    /**
     * @param bool $viewContentLink
     *
     * @return $this
     */
    public function setViewContentLink($viewContentLink)
    {
        $this->addModifiedProperty('viewContentLink');
        $this->viewContentLink = $viewContentLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getBccAddress()
    {
        return $this->bccAddress;
    }

    /**
     * @param string $bccAddress
     *
     * @return $this
     */
    public function setBccAddress($bccAddress)
    {
        $this->addModifiedProperty('bccAddress');
        $this->bccAddress = $bccAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingDomain()
    {
        return $this->trackingDomain;
    }

    /**
     * @param string $trackingDomain
     *
     * @return $this
     */
    public function setTrackingDomain($trackingDomain)
    {
        $this->addModifiedProperty('tracingDomain');
        $this->trackingDomain = $trackingDomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getSigningDomain()
    {
        return $this->signingDomain;
    }

    /**
     * @param string $signingDomain
     *
     * @return $this
     */
    public function setSigningDomain($signingDomain)
    {
        $this->addModifiedProperty('signingDomain');
        $this->signingDomain = $signingDomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnPathDomain()
    {
        return $this->returnPathDomain;
    }

    /**
     * @param string $returnPathDomain
     *
     * @return $this
     */
    public function setReturnPathDomain($returnPathDomain)
    {
        $this->addModifiedProperty('returnPathDomain');
        $this->returnPathDomain = $returnPathDomain;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMerge()
    {
        return $this->merge;
    }

    /**
     * @param boolean $merge
     *
     * @return $this
     */
    public function setMerge($merge)
    {
        $this->addModifiedProperty('merge');
        $this->merge = $merge;

        return $this;
    }

    /**
     * @return string
     */
    public function getMergeLanguage()
    {
        return $this->mergeLanguage;
    }

    /**
     * @param string $mergeLanguage
     *
     * @return $this
     */
    public function setMergeLanguage($mergeLanguage)
    {
        $this->addModifiedProperty('mergeLanguage');
        $this->mergeLanguage = $mergeLanguage;

        return $this;
    }

    /**
     * @return array
     */
    public function getGlobalMergeVars()
    {
        return $this->globalMergeVars;
    }

    /**
     * @param array $globalMergeVars
     *
     * @return $this
     */
    public function setGlobalMergeVars(array $globalMergeVars)
    {
        $this->addModifiedProperty('globalMergeVars');
        $this->globalMergeVars = $globalMergeVars;

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipientMergeVars()
    {
        return $this->recipientMergeVars;
    }

    /**
     * @param string $toMail
     * @param array $mergeVars
     *
     * @return $this
     */
    public function setMergeVarsForRecipient($toMail, array $mergeVars)
    {
        $this->addModifiedProperty('recipientMergeVars');
        $this->recipientMergeVars[] = [
            'recipient' => $toMail,
            'vars' => $mergeVars
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->addModifiedProperty('tags');
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubAccount()
    {
        return $this->subAccount;
    }

    /**
     * @param string $subAccount
     *
     * @return $this
     */
    public function setSubAccount($subAccount)
    {
        $this->addModifiedProperty('subAccount');
        $this->subAccount = $subAccount;

        return $this;
    }

    /**
     * @return array
     */
    public function getGoogleAnalyticsDomains()
    {
        return $this->googleAnalyticsDomains;
    }

    /**
     * @param array $googleAnalyticsDomains
     *
     * @return $this
     */
    public function setGoogleAnalyticsDomains(array $googleAnalyticsDomains)
    {
        $this->addModifiedProperty('googleAnalyticsDomains');
        $this->googleAnalyticsDomains = $googleAnalyticsDomains;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleAnalyticsCampaign()
    {
        return $this->googleAnalyticsCampaign;
    }

    /**
     * @param string $googleAnalyticsCampaign
     *
     * @return $this
     */
    public function setGoogleAnalyticsCampaign($googleAnalyticsCampaign)
    {
        $this->addModifiedProperty('googleAnalyticsCampaign');
        $this->googleAnalyticsCampaign = $googleAnalyticsCampaign;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata)
    {
        $this->addModifiedProperty('metadata');
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipientMetadata()
    {
        return $this->recipientMetadata;
    }

    /**
     * @param string $toMail
     * @param array $metadata
     *
     * @return $this
     */
    public function setRecipientMetadata($toMail, array $metadata)
    {
        $this->addModifiedProperty('recipientMetadata');
        $this->recipientMetadata[] = [
            'recipient' => $toMail,
            'vars' => $metadata
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param string $filePath
     * @param string $newFilename
     *
     * @return $this
     */
    public function addAttachment($filePath, $newFilename = null)
    {
        $this->addModifiedProperty('attachments');
        $this->attachments[] = [
            'path' => $filePath,
            'name' => $newFilename
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param string $imagePath
     * @param string $imageName
     *
     * @return $this
     */
    public function addImage($imagePath, $imageName)
    {
        $this->addModifiedProperty('images');
        $this->images[] = [
            'path' => $imagePath,
            'name' => $imageName
        ];

        return $this;
    }
}
