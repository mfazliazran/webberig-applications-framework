# Introduction #

The framework includes a _.htaccess_ file containing rewrite rules for Apache's mod\_rewrite.

This will only work for Apache webservers with the mod\_rewrite module active. More information can be found here: http://httpd.apache.org/docs/current/mod/mod_rewrite.html

Every page request is directed to index.php, which can be called the [BootStrapper](BootStrapper.md). After preparing some other things, it will call Waf::ProcessURL which will define the include\_file property according to some code logic.

Now that the framework knows which page the user is after, it will call Waf::DoOutput() to start processing the page and the templates.

# Rewrite map #

## Pages ##
  * Example: {basepath}myPage.htm
  * Rewritten URL: index.php?page=myPage
  * Waf->include\_file: /application/pages/myPage.php

## Modules ##
  * Example: {basepath}account/logout
  * Rewritten URL: index.php?module=account&action=logout
  * Waf->include\_file: /application/modules/account/logout.php

You don't need to provide an action. In that case the framework will use /module/index.php instead:

  * Example: {basepath}account
  * Rewritten URL: index.php?module=account
  * Waf->include\_file: /application/modules/account/index.php

You can also provide an additional value:
  * Example: {basepath}users/edit/3
  * Rewritten URL: index.php?module=users&action=edit&value=3
  * Waf->include\_file: /application/modules/users/edit.php
  * $_GET['value'] is set and has the value '3'_

Note: You can only provide a value if you also provide an action. If you need to use values in the index page, you must do this as follows:
  * Example: {basepath}users/index/3
  * Rewritten URL: index.php?module=users&action=index&value=3
  * Waf->include\_file: /application/modules/users/index.php
  * $_GET['value'] is set and has the value '3'_

## Public pages ##
Public pages use the same code as the modules:
  * Example: {basepath}myPublicPage
  * Rewritten URL: index.php?module=myPublicPage
  * Waf->include\_file: /application/public/myPublicPage.php

The public page will only be available if no module with the same name exists. A logged on user can also visit public pages, but users that aren't logged in won't have access to modules obviously.

## API ##
The API has a similar rewrite map:

  * Example: {basepath}api/object
  * Rewritten URL: api.php?controller=object

  * Example: {basepath}api/object
  * Rewritten URL: api.php?controller=object

  * Example: {basepath}api/object/3
  * Rewritten URL: api.php?controller=object&value=3

Used API class: /application/api/object.class.php