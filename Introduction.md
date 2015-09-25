

# Introduction #
This project is called Webberig Application Framework. Let's break this down:

**Webberig**: This word is a dutch adjective for the word "web" and can be loosely translated as "Webby" or "Web-like". The word can't be found in any dictionary, or it's no common slang. So here's the anekdote:

Me and some friends were looking for a suitable name for a project. During that brainstorming session many names were mentioned and immediately rejected by the others. At some point, someone said "That name isn't very webby (webberig)". We all laughed and suddenly realized we found the name. Unfortunately, the project itself didn't get off the ground. The name was stuck in my mind, and after a while I decided to use it as my professional pseudonym.

So, in short, Webberig is the company/author's name.

**Application**: The framework is intended for creating web applications.

**Framework**: This project started as a single file which I used as a boilerplate to start new projects (both websites and applications). Every time I worked on a project, I came across some bugs, short comings and things I just wanted to add in the template. So, the single file became more files, and kept on growing until it actually became a framework covering all basic things you may want to have in every web application.

# Features #
These are the key features of the framework:
## URL rewriting ##
The framework contains mod\_rewrite rules which point all HTTP request to the bootstrapper. Since there are several ways to create your content, there are a few different ways how the url will look.

**Want to know more ?**
  * [Detailed overview from the URL rewrite map](UrlRewriteMap.md)

## Authentication ##
The applications framework is intended for applications that require authentication. Therefor it contains some basecode to have a login window, and secure the session with a fingerprint hash to avoid session jacking.

The modules _Account_, _Roles_ and _Users_ are present by default to manage your application credentials. According to your needs you may need to change the way these modules works, but in the most common cases these can be left untouched.

Google's federated login was recently added to the framework. This allows a user to link his credentials to his Google account, so he/she can login by clicking the "Google" button on the login page. This is also the first step for implementing the application on the Google Apps marketplace.

**Want to know more ?**
  * [core/Security.class.php reference](CoreSecurityClass.md)
  * [Account module reference](AppModulesAccount.md)
  * [Roles module reference](AppModulesRoles.md)
  * [Users module reference](AppModulesUsers.md)

## Several ways to create content ##
The page content can be created in different ways:
  * **Pages**: Single pages that define content as a stand-alone entity
  * **Modules**: A module is a group of pages that share a common function or business entity, ie. Users. The module would contain pages to show a list, view details and edit, add and delete items.
  * **Public pages**: Public pages are the same as normal pages, but a user doesn't need to be logged in to access these pages.

**Want to know more ?**
  * [Creating a page](HowToCreatePages.md)
  * [Creating a module](HowToCreateModule.md)
  * [Public pages](PublicPages.md)

## A flexible template system ##
The template system in the framework starts with a "master page", which is basically a complete HTML file. The HTML file contains special tags that define locations for certain content:
  * **Body**: This tag is required, as this will contain the output of the requested page. It looks like this: <%body%>
  * **Applets**: Applets are small files that may included at certain locations in the page, but are not directly related to the body.
  * **Views**: Views work the same way as applets, but are a bit more complexe. Where an applet is nothing more then an included php file with sequential code, a View is a class.

Pages have full control over the HTML template, so it's possible to have multiple HTML files and define which HTML file should be used.

**Want to know more ?**
  * [Creating an HTML template](HowToCreateHTml.md)
  * [Creating an applet](HowToCreateApplet.md)
  * [Creating a view](HowToCreateView.md)
  * [Using a view in pages](HowToUseViews.md)
  * [How do CSS files work?](HowToCSS.md)

## MySQL connection management ##
The framework contains a class that will deal with the MySQL connection and provide some utilities to execute MySQL queries very easily. It protects queries from SQL injection and the MySQL object allows you to easily generate INSERT and UPDATE queries from arrays.

**Want to know more ?**
  * [Executing a parameterized MySQL query](HowToMySQLExecQuery.md)
  * [Creating and executing an INSERT query](HowToMySQLGenerateInsert.md)
  * [Creating and executing an UPDATE query](HowToMySQLGenerateUpdate.md)
  * [core/MySQL\_Query.class.php reference](CoreMySQLQueryClass.md)

## Forms library ##
The Waf\_Forms library contains a set of objects to deal with forms. The form object is a collection of inputs, validators and actions.
  * **Inputs** are objects that will generate the HTML inputs.
  * **Validators** do what the name says: validate stuff. Each validator contains parameters needed to perform the purposed validation.
  * **Actions** are performed after a form is submitted and it has been validated. Actions can send an email, redirect to a different page, etc.

Also note that the Waf\_Forms library can be used outside the framework.

**Want to know more ?**
  * [Creating a form](HowToCreateForm.md)
  * [Waf\_Forms library overview](WafForms.md)

## Logging ##
The framework has implemented Apache's php4log. This library is based on log4j, a wellknown logging library.
  * [How to use logging](HowToUseLogging.md)