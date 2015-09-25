# Introduction #
The framework uses log4php for logging, a well known logging framework based on the popular log4j library.

# Details #
Using log4php is very easy. First, you need to instanciate a logger. Using that logger, you can relay several types of messages
```
    $logger = LoggergetLogger(Application.Classes.Users);
    $logger-debug(This is a debug message);
    $logger-info(This is an info message);
    $logger-error(This is an error message);
```

The framework contains 2 different configuration files for log4php which, by default, outputs logs in XML format to the _logs_ directory.

The debugging configuration file will be used if the Debugging setting is set to true. Log4php will generate more detailed logs.

These XML files can be read by any number of applications ie. log4view.

The configuration file is _applicationlibrarieslog4phpconfig.xml_.