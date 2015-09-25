# Introduction #
The framework supports the use of Views, basically the same way as other MVC frameworks do.

A view is a class which may contain some properties and/or methods. As soon as an instanciated view is parameterized the way you want, the required method `Output()` can be called to start the output.

Views serve multiple purposes. You can use them in pages or you can refer to them directly in an HTML master pages.

# Details #
Views are located in the folder _/application/views_. To create a new view, start by creating a new file in this folder. For example, itemList.php:
```
<?php
class itemList extends ViewMaster
{
    public function Output()
    {
    }

}
?> 
```
Please note the following:
  * Make sure the filename is equal to the class name. Linux webservers are case sensitive!
  * The class must extend `ViewMaster` in order to work properly.
  * finally it must contain the `Output()` method.

The class will have property overloading, so you don't have to define your properties if you don't want to.

# using Views #
You can use a view directly from an HTML master page. This is explained [here](HowToCreateHTml#Using_a_view.md) in detail.

Consider the following code for using a view:
```
$view = ViewMaster::Create("itemList");
$view->data = Jobs::GetList();
$view->Output();
```
  * Always use the `ViewMaster::Create` factory method.
  * The objects use property overloading, so you can set certain properties dynamically
  * Call the {{{Output()}} method when you want the view to do its work.