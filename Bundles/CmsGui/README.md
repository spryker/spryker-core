# CmsGui Module

## Installation

```
composer require spryker/cms-gui

```

The following information describes how to install the newly released ´Product´ bundle (02/2017).
These instructions are only relevant if you need to add this bundle to an already installed version of the Framework.
If you have not yet installed the Spryker Framework, ignore these instructions as include this bundle in all versions released after February 2017.

To enable this bundle you need to change your project navigation configuration file `/config/Zed/navigation.xml`, replace `<cms/>` with `<cms-gui/>`.

The new cms gui bundle threats data a bit differently, so you may see data represented differently.
The main difference that each page entity is now holding multiple url so it means you create one page with multiple translations.

Old behaviour was that each page belongs to one url.

Each page may have prefix appended (/en/url /de/url, etc..) this is disabled by default in CMS bundle config for BC reasons. 
To enable it overwrite `CmsConfig::appendPrefixToCmsPageUrl` method in project and return `true` value.

## Documentation

[Documentation](https://spryker.github.io)


