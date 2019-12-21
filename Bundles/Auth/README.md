# Auth Module
[![Build Status](https://travis-ci.org/spryker/auth.svg)](https://travis-ci.org/spryker/auth)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)

Auth is part of the Store Administration Functionality and is used for managing the user authentication for the Zed Administration Interface. This module manages the authorization of a specific user by returning true or false if the credentials are allowed to access the system or not. It is used for login, logout, and used by the login controller to verify if a given user token is authenticated. Login is authenticated with a form or a header (via token). Auth is also used to validate that Zed has authorization to process incoming requests from Yves or 3rd parties such as payment providers.

## Installation

```
composer require spryker/auth
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/user_rights_management.html)
