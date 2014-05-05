Silverstripe DMS Simple Tags management
=======================================

Simple tags management for the Document Management System (DMS)

Requirements:
- Silverstripe 3.0+
- Silverstripe DMS (https://github.com/silverstripe-labs/silverstripe-dms)
- Silverstripe Quickaddnew (https://github.com/sheadawson/silverstripe-quickaddnew)

Install with composer:
----------------------

- Add `"tubbs/silverstripe-dms-simple-tags": "dev-master"` to your composer.json file
- Run `composer  update`
- Run `sake dev/build flush=1`


You will now have a ListboxField to select multiple tags and an 'Add New' button in a DMSDocument form in the CMS. There is also a checkbox to show or hide tags in the frontend templates on a per-document basis.
