# DrsWorld Task



## How to build the project:

At first you have to run:


At first you have to add all required Packages to 
composer.json file before running composer install command: 

```json{
"require": {
   "elasticsearch/elasticsearch": "^7.5",
   "pda/pheanstalk": "^4.0",
   "predis/predis": "^1.1"
}
```

Then run:

```bash
composer install
```

After composer install create .env file by running:

```bash
mv .env.example .env
```

Also be sure that all of this required attributes exist
with this values:

```bash
CACHE_DRIVER=redis
QUEUE_CONNECTION=beanstalkd
ELASTICSEARCH_ENABLED=true
ELASTICSEARCH_HOSTS="localhost:9200"
REDIS_CLIENT=predis
```

Then generate application encryption key:

```bash
php artisan key:generate
```

Hint:

```text
Before running this application you have to be sure that
your machine has (beanstalk and redis and elasticsearch) installed.
```

Then to make the project up and running:

```bash
php artisan serve
```

Also to run tests use:

```bash
./vendor/bin/phpunit tests/Feature/Feedback/CreateFeedbackTest.php
```

## Usage

after running the app on laravel default port (8000)
call this route for creating feedback:

```text
http://localhost:8000/api/feedbacks (POST Request)
```

For creating feedback,
you have to send:

```text
company_token: string
priority: enum('major','minor','central')
device: string
os: string
memory: string
storage: string
```

if any of this was sent in a wrong format 
validation error appears
call this route for creating feedback:

Other requests are (GET Requests) use them as mentioned in the task.

# Task (Hints) in details:

 Inside Search folder i created a FeedbackInterface class
 and two different implementation for it, one implementation
 for using eloquent as main search engine and the second
 one is for elasticsearch.

 ## Response Interface:
 Inside Http folder i created a Responses folder that
 contains: (ResponsesInterface.php) & (ApiResponder.php)
 for using the ApiResponder class as the concrete 
 implementation for the ResponsesInterface
 
 ```text
 Inside AppServiceProvider I am binding ResponsesInterface 
 to it's implementation (ApiResponder) for handling all 
 response inside the app to fit APIS (JSON Response)
 instead of laravel default response. 
 ```
 
 ```php
 // Use the ApiResponder as the concrete implementation for the ResponsesInterface
 $this->app->bind(ResponsesInterface::class, ApiResponder::class);
 ``` 
 
 ## Exception Interface
 
 For making exceptions response fit APIs:
 
 ```text
 Inside AppServiceProvider I am binding Laravel's ExceptionHandler 
 Interface to it's new implementation (ApiHandler) for changing 
 laravel default response to (API JSON RESPONSE). 
 ```
 
 ```php
// Use the ApiHandler as the main exception handler
$this->app->singleton(ExceptionHandler::class, ApiHandler::class);
 ``` 

 

