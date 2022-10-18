# Mail Module
[![Latest Stable Version](https://poser.pugx.org/spryker/mail/v/stable.svg)](https://packagist.org/packages/spryker/mail)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg)](https://php.net/)

Mail module helps to create emails to be sent. To send mails, Mail uses the email provider defined for the project and each provider behaves differently. One takes a fully rendered template and sends it, one just receives a request with query params and another one only works with a well formatted JSON. There are many ways Mail providers do their job. Therefore Mail module is suited to work easily with a wide range of providers. To get started a simple provider has been included. The default provider uses SwiftMailer.

## Installation

```
composer require spryker/mail
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)
