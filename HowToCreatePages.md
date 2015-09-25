# Introduction #
PHP usually sends the HTTP headers to the client as soon as your code has started output. Those headers contain important information for the client, such as the status code (ie. 404 not found, 403 not authorized) or the "location" header for redirecting to a different page.

Because of that, it makes perfect sense to maintain the following steps:
- Make sure you know what content you want to output, and how
- Start your output

In short, it's a _best practice_ to perform all necessary actions and prepare your output **before** actually starting your output.

This logic is translated by having 2 seperate parts in every php file that acts as a page:
  * All content inside modules (ie. /application/modules/xxx.php)
  * All normal pages inside /application/pages
  * Public pages located in /application/public

# How to code your page #
During the process of Waf::DoOutput(), it will include the chosen page twice during these steps:
  * Set the local variable $p to **true**
  * include the file
  * starting output (which means loading the HTML master page)
  * set the local variable $p to **false**
  * include the file again when the `<%body%>` is found in the HTML masterpage

More information on this process can be [found here](RequestProcess.md).

So, when you create a page, consider this snippet:
```
<?php
if ($p)
{
  // Prepare your output
} else {
  // Do your output
}
?>
```

The code will be run _sort of_ sequentially. During the first include() $p will be true, so the first part "preparation" is run. The second time $p will be false so the second part "output" is run.

Things you should keep in the upper part of your page:
  * Run MySQL queries (1)
  * Try to perform redirects
  * Process a form or any other input

<sub>(1) It may even be better putting your MySQL queries in a class function and calling that method instead of putting those in your page</sub>

# Using Waf properties #

During the prep phase of your page, you may need to define certain parameters, which are used in different parts of your application, such as applets or views.

The Waf object allows you to do just that, since it uses [member overloads](http://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members)

An example:
```
<?php
if ($p)
{
  $this->pageTitle = "My page title";
  $this->menu = ViewMaster::Create("Menu");
  $this->menu->activeMenu = "myPage";
} else {
  // Do your output
}
?>
```
We can use **$this** in pages since the includes are done from within the **Waf object**, maintaining the scope of the Waf object.

As you can see here, the example sets a page title, it creates a view called "Menu" and sets a property in the view.

# Using MVC #

If you are used at using the MVC pattern, this may all look very chaotic. However, this framework supports the use of views. Consider this example:

```
<?php
if ($p)
{
  $view = ViewMaster::Create("myPageView");
  $view->title = "some page title";
  $view->data = Pages::GetItem("about_us");
} else {
  $view->Output();
}
?>
```

  * The page acts as the **controller**, by preparing the view, and putting the necessary data in the view object
  * The **view** is created using ViewMaster::Create()
  * The static function Pages::GetItem("about\_us") retrieves the page from wherever you want it, and returns a **model** that your view can use