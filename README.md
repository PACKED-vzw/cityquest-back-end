# cityquest-mobile
 CityQuest allows cultural organisations to easily create a quest online, and publish it to a mobile app.   Send your visitors around the city to discover items from your collection and the locations they are connected to.   Based on hints and media you track down an item, scan the QR code on its location and learn the (hi)story behind it.
 
 This is the back-end application, responsible for creating and providing quests.
 
 Documentation may be found at the [AthenaPlus wiki](http://wiki.athenaplus.eu/index.php/CityQuest).
 
 Cityquest was created by [PACKED vzw](http://packed.be/) as part of the [AthenaPlus project](http://www.athenaplus.eu/) funded by the European Commission.


## Installation instructions
Create a MySQL database and user (in MySQL) and update your parameters.yml (in app/config) with the database server, database name, user name and password.

Install all dependencies using the composer.json-file included in this Git repository.

Create the tables this application uses with the following command (executed in the root of your Symfony application):
```
php app/console doctrine:schema:update --force
```

Execute this command to allow [FOSjsRouting](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md) to work:
```
php app/console assets:install --symlink web
```

### Permissions
You need to make sure that the web server user (e.g. www-data on Ubuntu) has write rights to the application sub tree.

In some cases, the directory `web/resources` is not created properly. If so, create it manually and make sure the web server user has write rights to this directory.

## User administration
Cityquest requires at least one "administrator" account to function. You can create this account by executing the following command (executed in the root of your Symfony application):
```
php app/console fos:user:create Admin admin@some-email.em some_password --super-admin
```

### Resetting user passwords
The interface does not provide a way to reset user passwords. This can be done using the CLI interface however:
```
php app/console fos:user:change-password Admin some_other_password
```

For more commands relating to user administration, see the documentation of [FOSUserBundle](https://symfony.com/doc/master/bundles/FOSUserBundle/command_line_tools.html).
