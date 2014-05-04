DMS Simple Tags management
==========================

Simple tags management for the Document Management System (DMS)

Requirements:
- Silverstripe 3.1+
- Silverstripe DMS (https://github.com/silverstripe-labs/silverstripe-dms)
- Silverstripe Quickaddnew (https://github.com/sheadawson/silverstripe-quickaddnew)

Install with composer:
----------------------

- Add `"tubbs/dms-simple-tags": "dev-master"` to your composer.json file
- Run `composer  update`
- Run `sake dev/build flush=1`
- That's it, you will now have a ListboxField to select multiple tags and an 'Add New' button in a DMSDocument form in the CMS.