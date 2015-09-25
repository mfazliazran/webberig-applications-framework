# Introduction #
While I haven't seen this approach in any PHP framework or template system yet, I think the framework's template system looks a lot like the ASP.NET masterpages.

# Details #

HTML masterpage files are usually in the _/application/layout/html_ directory. You can name the file whatever you want.

Unless you provide a different html design in each page, the name default.html will be used.

The HTML masterpage is a complete HTML file, which starts with the doctype (at least, it should!) and ends with the HTML closing tag. Using special tags similar to class ASP (_`<% %>`_) you can pinpoint certain places in the file which need to be dynamic.

Example:
```
<doctype html>
<html>
  <head>
<%htmlheaders%>
  </head>
  <body>
<%doctitle%>
<%body%>
  </body>
</html>

```

The tag `<%body%>` is required, your pages won't work (properly) if your masterpage is missing this tag!

The others are dynamic. Every special tag will be checked against the available applets.
ie. `<%htmlheaders%>` => /application/layout/applets/htmlheaders.php will be included during the output phase.

# Using a view #
If the framework finds the tag name in the applets folder, the applet will be used. If not, it will automatically check for a view inside the _/application/views_ folder.

If a view is found, the framework will automatically instanciate that view and call the _Output()_ method immediately.

You can also add a querystring to the special tag for views:
```
<body>
  <header>
    <%menu?level=1%>
  </header>
<div id="side">
 <%menu?level=2%>
</div>
</body>
```
The given example will use 2 different instances of the menu view, located in the file _/application/views/menu.php_.

As you can see, both tags contain a parameter _level_. The framework will automagically perform following code:
```
<?php
$view = ViewMaster::Create("menu");
$view->level = 1; // or 2 for the second tag
$view->Output();
?>
```