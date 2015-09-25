# Introduction #

The MySQL object contains a function that helps you create and execute INSERT queries. It basically turns an associative array into a parameterized query, which you can then execute the query the usual way.

# Details #
To create an INSERT query, you must have an associative array:
```
$values = array();
$values['name'] = "John";
$values['email'] = "john@google.com";
```

The keys should be equal to the table field names. The values are obviously the values that you want to insert.

When the array is good to go, all you need to do is the following:
```
$f = Waf::Singleton();
$qry = $f->NewQuery();
$qry->GenerateInsertQuery("users", $values);
$qry->Exec();
```

The first parameter of the _GenerateInsertQuery_ method is the name of the table. If you defined a table prefix in the settings, it will be added automatically. The second parameter is the array you wish to insert.

Just to be clear, the example above will execute following query:
```
INSERT INTO prefix_users (name, email) VALUES ('John', 'john@google.com');
```