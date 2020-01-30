# SuperSaaS PHP SDK

Online bookings/appointments/calendars in PHP using the SuperSaaS scheduling platform - https://supersaas.com

The SuperSaaS API provides services that can be used to add online booking and scheduling functionality to an existing website or CRM software.

## Prerequisites

1. [Register for a (free) SuperSaaS account](https://www.supersaas.com/accounts/new), and
2. get your account name and API key on the [Account Info](https://www.supersaas.com/accounts/edit) page.

##### Dependencies

PHP 5.4 or greater.

No external libraries. Only the native `json_encode`/`json_decode` and `stream_context_create` standard calls are used.

## Installation

1: Composer

The SuperSaaS PHP API Client is available from Packagist and can be included in your project via composer. Note, the supersaas-api-client may update major versions with breaking changes, so it's recommended to use a major version when expressing the package dependency. e.g.

    $ composer require "supersaas/api-client:0.9.*"
    
    
In `composer.json`:

    {
        "require": {
            "supersaas/supersaas-api-client": "^1"
        }
    }

2: Manual

Download or checkout the project from github, and include the `src/SuperSaaS` folder manually.

## Configuration
    
The `Client` can be used either (1) through the singleton helper method `Instance`, e.g.
    
    SuperSaaS\Client::Instance(); //=> Client

And configured with authorization credentials using the `configure` method:

    SuperSaaS\Client::configure('accountname', 'apikey');
    
Or else by (2) simply creating a new client instance and setting the properties manually, e.g.
    
    $client = new Supersaas\Client();
    $client->account_name = 'accountname';
    $client->api_key = 'apikey';

> Note, ensure that `configure` is called before `Instance`, otherwise the client will be initialized with configuration defaults.

If the client isn't configured explicitly, it will use default `ENV` variables for the account name and api key.

Set these `ENV` variables before calling the client. 

    putenv("SSS_API_ACCOUNT_NAME=your-env-supersaas-account-name");
    putenv("SSS_API_KEY=your-env-supersaas-account-api-key"); 
    SuperSaaS\Client::Instance()->account_name; //=> 'your-env-supersaas-account-name'
    SuperSaaS\Client::Instance()->api_key; //=> 'your-env-supersaas-account-api-key'

All configuration options can be individually set on the client.

    SuperSaaS\Client::Instance()->api_key = 'xxxxxxxxxxxxxxxxxxxxxx'; 
    SuperSaaS\Client::Instance()->verbose = true;
    ...

## API Methods

Details of the data structures, parameters, and values can be found on the developer documentation site:

https://www.supersaas.com/info/dev

#### List Schedules

Get all account schedules:

    SuperSaaS\Client::Instance()->schedules->list(); //=> array(Schedule, ...)

#### List Resource

Get all services/resources by `schedule_id`:

    SuperSaaS\Client::Instance()->schedules->resources(12345); //=> array(Resource, ...)    

_Note: does not work for capacity type schedules._

#### Create User

Create a user with user attributes params:

    SuperSaaS\Client::Instance()->users->create(array('full_name' => 'Example Name', 'email' => 'example@example.com')); //=> User

#### Update User

Update a user by `user_id` with user attributes params:

    SuperSaaS\Client::Instance()->users->update(12345, array('full_name' => 'New Name')); //=> array()
    
#### Delete User

Delete a single user by `user_id`:

    SuperSaaS\Client::Instance()->users->delete(12345); //=> array()
    
#### Get User

Get a single user by `user_id`:

    SuperSaaS\Client::Instance()->users->get(12345); //=> User

#### List Users

Get all users with optional `form` and `limit`/`offset` pagination params:

    SuperSaaS\Client::Instance()->users->list(date('Y-m-d H:i:s'), 25, 0); //=> array(User, ...)

#### Create Appointment/Booking

Create an appointment by `schedule_id` and `user_id` with appointment attributes and `form` and `webhook` params:

    SuperSaaS\Client::Instance()->appointments->create(12345, 67890, array('full_name' => 'Example Name', 'email' => 'example@example.com', 'slot_id' => 12345), true, true); //=> Appointment

#### Update Appointment/Booking

Update an appointment by `schedule_id` and `appointment_id` with appointment attributes params:

    SuperSaaS\Client::Instance()->appointments->update(12345, 67890, array('full_name' => 'New Name')); //=> array()

#### Delete Appointment/Booking

Delete a single appointment by `schedule_id` and `appointment_id`:

    SuperSaaS\Client::Instance()->appointments->delete(12345, 67890); //=> array()

#### Get Appointment/Booking

Get a single appointment by `schedule_id` and `appointment_id`:

    SuperSaaS\Client::Instance()->appointments->get(12345, 67890); //=> Appointment

#### List Appointments/Bookings

Get agenda (upcoming) appointments by `schedule_id` and `user_id`, with `form` and `slot` view params:

    SuperSaaS\Client::Instance()->appointments->list(12345, 67890, true, true); //=> array(Appointment, ...)

#### Get Agenda

Get agenda (upcoming) appointments by `schedule_id` and `user_id`, with `form` and `slot` view params:

    SuperSaaS\Client::Instance()->appointments->agenda(12345, 67890, true, true); //=> array(Appointment, ...)

#### Get Available Appointments/Bookings

Get available appointments by `schedule_id`, with `from` time and `length_minutes` and `resource` params:

    SuperSaaS\Client::Instance()->appointments->available(12345, '2018-01-31 00:00:00', 15, 'My Class'); //=> array(Appointment, ...)

#### Get Recent Changes

Get recently changed appointments by `schedule_id`, with `from_time` view param:

    SuperSaaS\Client::Instance()->appointments->changes(12345, '2018-01-31 00:00:00', true); //=> array(Appointment, ...)

#### Get Recent Changes Slots

Get recently changed appointment slots by `schedule_id`, with `from_time` view param:

    SuperSaaS\Client::Instance()->appointments->changes(12345, '2018-01-31 00:00:00', true); //=> array(Slot, ...)
    
#### Get list of appointments

Get list of appointments by `schedule_id`, with `today`, `from time`, `to` time and `slot` view param:

    Client.instance().appointments.range(schedule_id=12345, today=True, from_time='2020-01-31 00:00:00',from_time='2020-02-01 00:00:00' slot=False)

#### List Template Forms

Get all forms by template `superform_id`, with `from` time param:

    SuperSaaS\Client::Instance()->forms->list(12345, '2018-01-31 00:00:00'); //=> array(Form, ...)

#### Get Form

Get a single form by `form_id`:

    SuperSaaS\Client::Instance()->forms->get(12345); //=> Form

## Examples

The ./examples folder contains several executable PHP scripts demonstrating how to use the API Client for common requests.

The examples will require your account name, api key, and some of the examples a schedule id and/or user id and/or form id. These can be set as environment variables. e.g.

    $ export SSS_API_UID=myuserid SSS_API_SCHEDULE=myscheduleid SSS_API_ACCOUNT_NAME=myaccountname  SSS_API_KEY=myapikey && php -f ./examples/appointments.php
    $ export SSS_API_FORM=myuserid SSS_API_ACCOUNT_NAME=myaccountname SSS_API_KEY=myapikey && php -f ./examples/forms.php
    $ export SSS_API_ACCOUNT_NAME=myaccountname && export SSS_API_KEY=myapikey && php -f ./examples/users.php

## Testing

The HTTP requests can be stubbed by configuring the client with the `dry_run` option, e.g.

    SuperSaaS\Client::Instance()->dry_run = true;

Note, stubbed requests always return an empty array.

The `Client` also provides a `last_request` attribute containing the http array object from the last performed request, e.g. 

    SuperSaaS\Client::Instance()->last_request; //=> array('method' => ..., 'header' => ..., 'content' => ...)

The headers, body, etc. of the last request can be inspected for assertion in tests, or for troubleshooting failed API requests.

For additional troubleshooting, the client can be configured with the `verbose` option, which will `puts` any JSON contents in the request and response, e.g.

    SuperSaaS\Client::Instance()->verbose = true;

## Additional Information

+ [SuperSaaS Registration](https://www.supersaas.com/accounts/new)
+ [Product Documentation](https://www.supersaas.com/info/support)
+ [Developer Documentation](https://www.supersaas.com/info/dev)
+ [Python API Client](https://github.com/SuperSaaS/supersaas-python-api-client)
+ [Ruby API Client](https://github.com/SuperSaaS/supersaas-ruby-api-client)
+ [NodeJS API Client](https://github.com/SuperSaaS/supersaas-nodejs-api-client)

Contact: [support@supersaas.com](mailto:support@supersaas.com)

## Releases

The package follows [semantic versioning](https://semver.org/), i.e. MAJOR.MINOR.PATCH 

## License

The SuperSaaS PHP API Client is available under the MIT license. See the LICENSE file for more info.
