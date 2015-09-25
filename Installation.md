# Introduction #

This page will explain how you can start your own application using the framework.

# Details #

## Step 1: Downloading the framework ##
Download the latest version of the framework [http://webberig-applications-framework.googlecode.com/files here](here.md)

It's highly recommended to use a stable release (minor version = even number). If you like to do some beta testing, you should download the latest unstable release (minor version = odd number).

## Step 2: Creating a database ##
Create a database and run the structure.sql file included in the package.

The script also inserts some initial records:
  * A 'root user' role, which has access to the roles module
  * A user '**admin**' with password '**Waf1234**'

## Step 3: Configure the application ##
Go to the file _/application/settings.php_ and change this file with the right settings. The most important settings are the MySQL connection settings and the BasePath.

## Step 4: Run your application ##
That's it! You should now be able to run your application through your web server!

The newly created user will only have access to the roles module. When you log on, you will probably see nothing but an empty screen and the dashboard. Visit the url _http://path\_to\_your\_app/roles_ and change the Root user role to grant access to all available modules.