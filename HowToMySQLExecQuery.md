# Introduction #
The framework includes functions that make MySQL queries safe and easy. It allows you to parameterize queries.

# Details #
First you need to have access to the Waf object. Pages and applets are in the scope of the Waf object, so you can use `$this`. If not, you need to get the Waf object. It uses a singleton pattern:
```
$f = Waf::Singleton();
```

Once you have the reference to the Waf Object (`$f` or `$this`), you can create the Query object:
```
$qry = $f->NewQuery("SELECT * FROM %PRE%users WHERE id = @id");
```
The framework automatically replaces `%PRE%` with the prefix defined in the settings. The `@id` that you can see in the query is a parameter. In order for this to work, we must give that parameter a value:
```
$qry->setParam("id", 1);
```
The first parameter of the `setParam` method is the name of the parameter, the second is the value.

Now that we've defined the MySQL query and the needed parameters are set, we can execute the query:
```
$ret = $qry->Exec();
```
The `Exec()` function works the same way as the plain old `mysql_query()` function, it returns the resource id which you can use like you would normally do:
```
$count = mysql_num_rows($ret);
while ($row = mysql_fetch_assoc($ret)
{
  ...
}
```