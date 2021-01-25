# Mail Module
[![Build Status](https://travis-ci.org/spryker/mail.svg)](https://travis-ci.org/spryker/mail)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Mail module helps to create emails to be sent. To send mails, Mail uses the email provider defined for the project and each provider behaves differently. One takes a fully rendered template and sends it, one just receives a request with query params and another one only works with a well formatted JSON. There are many ways Mail providers do their job. Therefore Mail module is suited to work easily with a wide range of providers. To get started a simple provider has been included. The default provider uses SwiftMailer.

## Installation

```
composer require spryker/mail
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/mail/mail.html)
