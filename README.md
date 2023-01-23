# Laravel Sanctum Mongo CRUD API

Implementation of Sanctum based login using mongodb and performing CRUD operation.

### Step 1 - Install the PHP extension for MongoDB.

 - sudo pecl install mongodb
 - Enable the mongodb extension in your php.ini 

### Step 2 -- Add MongoDB package for Laravel
 - composer require jenssegers/mongodb

### Step 3 -- Add Mongodb configuration 
 - In config\database.php
 ##### 'connections' => [
 ##### 'mongodb' => [
 #####       'driver' => 'mongodb',
 #####        'dsn' => env('MONGO_URL', 'mongodb+srv://username:password@<atlas-cluster-uri>/myappdb?retryWrites=true&w=majority'),
 #####        'database' => env('DB_DATABASE'),
 ##### ],
 
 - In .env file
###### DB_CONNECTION=mongodb
###### DB_HOST=127.0.0.1
###### DB_PORT=3306
###### DB_DATABASE=db_name
###### DB_USERNAME=
###### DB_PASSWORD=
###### MONGO_URL=url_string

- Define Provider in app.php
##### Jenssegers\Mongodb\MongodbServiceProvider::class,
 
### Step 4 - Modify code in AppserviceProvider.php 
- See code in above repository

### Step 5 - Extend model with Jenssegers\Mongodb\Eloquent\Model;
- Check Post.php model
- For Authenticatable extend model with Jenssegers\Mongodb\Auth\User as Authenticatable;
- Check User.php model
