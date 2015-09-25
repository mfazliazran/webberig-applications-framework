# Introduction #
Modules are considered the most important building blocks for your application. Typically you would have a module for each business object in your application. The module would be responsible for viewing, editing, deleting etc. that particular object.

The framework contains some utilities to help you manage these blocks:
  * The [Modules](CoreModules.md) class allows you to access the available modules and get some details from them (such as icons, descriptions, ...)
  * The list of available modules and their specific access parameters are automatically detected by the roles module, which makes it possible to decide which modules are available to which roles.


# Details #
## Step 1 - Creating a folder ##
In order to create a new module, you should start by creating a new folder inside the _/application/modules_ folder. The name of the folder is used as the name of the module (ie. http://yourapp/module_name).

## Step 2 - Module.xml ##
Any folder in the _/application/modules_ folder will be ignored if there is nog module.xml inside it. So, you need to create this file, and it looks like this:
```
<?xml version="1.0" encoding="utf-8"?>
<module>
	<name>Roles</name>
	<icon>application/modules/roles/module.png</icon>
</module>
```

At the moment, there's only 2 parameters in the XML file:
  * Name: The human-readable name of the module (ie. used in navigation)
  * Icon: If you like to create an icon-based list of modules

Additional parameters could be added later on, or you can do this yourself by modifying the [Modules](CoreModules.md) class.

## Step 3 - index.php ##
Each module typically has an index.php file. This is the default 'action', used when the URL did not contain an action. (See [UrlRewriteMap](UrlRewriteMap.md) for details)

This file, as well as the additional actions created in step 4 use the typical 'page' structure described [here](HowToCreatePages.md).

While creating an index.php isn't really essential, it could make things easier if you create dynamic navigation bars.

## Step 4 - Additional actions ##
You could add extra actions. The name of the file should be equal to the url of the action, ie. http://yourapp/users/edit will point to /application/modules/users/edit.php.

## Step 5 - Define roles ##
You're all done! All you need to do now is make sure the proper roles in your application have access to the new module.

# Recycling modules #
The framework has a modular design which allows you to create modules for one application, and re-use them in another one by simply copying them to the modules directory.

That's the theory, anyway! There are some considerations:
  * You probably write your business code inside classes which all share the same directory _/application/classes_
  * Your module could rely on the use of certain libraries, which are located in _/application/libraries
  * When using relations between objects in the database, you might use functions from a class which is part of another module. Example: The_Users