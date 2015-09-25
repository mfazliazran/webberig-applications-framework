# Introduction #
The framework is intended for creating webapplications that require authentication to use them. People need to login to have access to the application.

Sometimes you may want to create certain pages that need to be available without logging in. Some examples:
  * Allowing people to register and creating a new account
  * An 'unsubscribe' link to your mass mailing application.
  * The framework includes an openID implementation for Google Federated Login. This page works much different compared to the login window, so the Google login uses a public page.

# Creating public pages #
Public pages should be created in the _/application/public_ directory. They basically work the same as normal pages or module actions:
  * You can use classes or the Waf object
  * The MySQL connection is present to perform queries
  * They use the same structure as regular pages. See [creating pages](HowToCreatePages.md).

The only difference is that you can't assume a user is logged on when the page is requested.

# Considerations #
If you're using public pages, there are a few considerations regarding your application:
  * Public pages use the same URL rewrite rule as modules. If a module exists with the same name, your public page will not work!
  * Your applets, views, classes should not assume the user is logged in, so you need to validate the use of `$_SESSION` variables, ie. an applet that shows the user's name should check if a user is even logged in.
  * **Public pages are visible to the outside world so make sure you don't expose any confidential data that isn't meant for public viewing**!
  * Be mindful of any kind of possible tampering with any input that your public pages require.

Example: An ecommerce application which sends a download URL to an invoice by email. The URL **could** look like this:
```
http://yourapplication/viewInvoice?id=2011-001
```
What keeps a user from trying out other ID's such as 2011-002 or 2011-003 ?

In this particular case, you could use at the very least a random key or code in the URL instead of the actual 'simple' ID:
```
http://yourapplication/viewInvoice?id=fdsfzj434FJfeye34jfdsh32432fdjhez
```