# Introduction #

The _Waf\_Mysql::GenerateUpdateQuery_ method works very similar to the [GenerateInsertQuery](HowToMySQLGenerateInsert.md).

# Details #
To create an UPDATE query, you must have an associative array:
```
$values = array();
$values['name'] = "John";
$values['email'] = "john@google.com";
```

The array keys should be equal to the table field names. The values are obviously the values that you want to insert.

Next, you must have an associative array that contains the query clauses:
```
$clause = array();
$clause['id'] = 1;
```

Using those queries, you can do the following:
```
$f = Waf::Singleton();
$qry = $f->NewQuery();
$qry->GenerateInsertQuery("users", $values, $clause);
$qry->Exec();
```

The first parameter of the _GenerateInsertQuery_ method is the name of the table. If you defined a table prefix in the settings, it will be added automatically. The second parameter is the array you wish to insert. The third parameter contains the clauses query.
The example above will execute following query:
```
UPDATE prefix_users SET name = 'John', email = 'john@google.com' WHERE id = 1;
```

If you use a clause array with 2 values, the WHERE clause will contain 2 comparisons with an AND operand:
```
UPDATE table SET xxx = 'xxx' WHERE key1 = 1 AND key2 = 1;
```