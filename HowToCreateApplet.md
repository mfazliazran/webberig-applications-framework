# Introduction #
If you take any website for example, the body isn't the only thing that changes in each page:
  * The navigation may have additional subitems for the active page
  * A sidebar could contain recent news items, a Twitter feed, etc ...
  * If the website allows you to login, it may contain a footer or header saying you're logged in or, if you're not logged in, it may show you the login form.

Applets are extensions to an HTML masterpage which allow you to make certain parts of the masterpage dynamic.

# Details #

Using applets is really simple. Once the output has started, the Waf object goes through the HTML masterpage sequentially. For each of those special tags (` <% ... %> `) it comes across, it will simply **include** the applet with the same name.

For example, `<%menu%>` will automatically result in the following code (this is the simplified version, ofcourse):
```
<?php
include("application/layout/applets/menu.php");
?>
```

In this simple example, your applet could contain the following code:
```
<ul>
  <li><a href="home">Home</a></li>
<?php
if (Security::Allowed("users") // Check if the user is allowed to do that
{
?>
  <li><a href="users">Users</a></li>
<?php
}
?>
</ul>
```
As you can see, an applet could also contain simple HTML without any PHP involved.

# Using Waf properties #
You may often find that applets need to generate certain content depending on some variables defined in the page itself. The Waf object supports property overloading, which makes that possible.

First, your page must define these variables in the preparation part of the page's code:
```
if ($p)
{
   $this->activeMenu = "shoppingbasket";
}
```
`$this` refers to the Waf object, since the include of the page is done in the Waf object's scope. The same thing happens with applets:
```
<ul>
   <li <?php echo (isset($this->activeMenu) && $this->activeMenu == 'home') ? ' class="active"' : ''?>>Home</li>
   <li <?php echo (isset($this->activeMenu) && $this->activeMenu == 'products') ? ' class="active"' : ''?>>Products</li>
   <li <?php echo (isset($this->activeMenu) && $this->activeMenu == 'shoppingbasket') ? ' class="active"' : ''?>>My basket</li>
</ul>
```
# Use cases #
There are several use cases for working with applets.

## Dynamic content ##
As explained in the introduction, the body of a page is not the only thing changing in a page. You may have navigation where the links are stored in the database, or you may want to show the shopping basket content in your webshop.

## Recycling ##
Applets often serve a general purpose which you may use in other projects as well. In example, generating a `<ul>` list of menu items.

If you keep a documented library of your applets, you don't need to code these dynamic parts in your designs twice. Just copy the applet to the _/application/layout/applets/_ folder and refer to the applet in the HTML masterpage.

## Global code ##
Let's assume your project uses many HTML masterpages, since your project requires several different page designs. These pages may still have some things in common, ie. most of the HEAD properties such as page title, common CSS or JS, etc ...

This common code doesn't need to be dynamic, it can just be static HTML.

If you put common code in an applet, you may find it easier in the future if you need to change anything to the common parts of the different designs.

## Libraries ##
The already present applet _htmlheaders.php_ contains this line:
```
$this->IncludeLibraries('head');
```

This line can be important if you use libraries. Libraries may need to inject certain HTML into the head (ie. links to JS or CSS), so this doesn't happen if you remove this line.