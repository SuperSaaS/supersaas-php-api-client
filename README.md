# SuperSaaS PHP SDK

Online bookings/appointments/calendars in PHP using the SuperSaaS scheduling platform - https://supersaas.com

The SuperSaaS API provides services that can be used to add online booking and scheduling functionality to an existing website or CRM software.

## Prerequisites

1. [Register for a (free) SuperSaaS account](https://www.supersaas.com/accounts/new), and
2. get your account name and API key on the [Account Info](https://www.supersaas.com/accounts/edit) page.

##### Dependencies

PHP 8.3 or greater.

No external libraries. Only the native `json_encode`/`json_decode` and `stream_context_create` standard calls are used.

## Installation

1: Composer

The SuperSaaS PHP API Client is available from Packagist and can be included in your project via composer. Note, the supersaas-api-client may update major versions with breaking changes, so it's recommended to use a major version when expressing the package dependency. e.g.

    $ composer require "supersaas/api-client:2.0.*"
    
    
In `composer.json`:

    {
        "require": {
            "supersaas/supersaas-api-client": "^2"
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
    SuperSaaS\Client::Instance()->verbose = TRUE;
    ...

## API Methods

Details of the data structures, parameters, and values can be found on the developer documentation site:

https://www.supersaas.com/info/dev

#### List Schedules

Get all account schedules:

    SuperSaaS\Client::Instance()->schedules->getList(); //=> array(Schedule, ...)

#### List Resource

Get all services/resources by `schedule_id`:

    SuperSaaS\Client::Instance()->schedules->resources(12345); //=> array(Resource, ...)    

_Note: does not work for capacity type schedules._

#### List Fields of a Schedule

Get all the available fields of a schedule by `schedule_id`:

    SuperSaaS\Client::Instance()->schedules->fieldList(12345); //=> array(FieldList, ...)

#### Create User

Create a user with user attribute params `create($attributes, $user_id = null, $webhook = null, $duplicate = null)`.
If `webhook=true` is present it will trigger any webhooks connected to the account.
To avoid a ‘create’ action from being automatically interpreted as an ‘update’, you can add the parameter duplicate=raise, then error `422 Unprocessable Entity` will be raised.
If in your database your user has id `1234` then you can supply a foreign key in format `1234fk` in `$user_id` (optional) which you can use to identify user:
If validation fails for any field then error `422 Unprocessable Entity` will be raised and any additional information will be printed to your log.
Data fields that you can supply can be found [here.](https://www.supersaas.com/info/dev/user_api):

    SuperSaaS\Client::Instance()->users->create(array('name' => 'name@name.com', 'full_name' => 'Example Name', 'email' => 'example@example.com')); //=> https://www.supersaas.com/api/users/1234.json

#### Update User

Update a user with `$user_id` using user attribute params `update($user_id, $attributes, $webhook=null, $notFound=null)`.
If `webhook=true` is present it will trigger any webhooks connected to the account.
To avoid automatically creating a new record, you can add the parameter `notfound=error` or `notfound=ignore` to return a 404 Not Found or 200 OK respectively.
If the `$user_id` does not exist 404 error will be raised.
You only need to specify the attributes you wish to update:

    SuperSaaS\Client::Instance()->users->update(12345, array('full_name' => 'New Name')); //=> array()
    
#### Delete User

Delete a single user by `user_id`:

    SuperSaaS\Client::Instance()->users->delete(12345); //=> array()
    
#### Get User

Get a single user by `$user_id`, and if the user does not exist 404 error will be raised:

    SuperSaaS\Client::Instance()->users->get(12345); //=> User

#### List Users

Get all users with optional `$form` and `$limit`/`$offset` pagination params, `getList($form=null, $limit=null, $offset=null)`.
User can have a form attached, and setting `form=true` shows the data:

    SuperSaaS\Client::Instance()->users->getList(true, 25, 0); //=> array(User, ...)

#### List Fields of User object

Get all the fields available to user object:

    SuperSaaS\Client::Instance()->users->fieldList() //=> array(FieldList, ...)

#### Create Appointment/Booking

Create an appointment with `schedule_id`, and `user_id(optional)` (see API documentation on [create new](https://www.supersaas.com/info/dev/appointment_api#bookings_api)) appointment attributes and optional `form` and `webhook` params,
`create($schedule_id, $attributes, $user_id, $form=null, $webhook=null)`:

    SuperSaaS\Client::Instance()->appointments->create(12345, 67890, array('full_name' => 'Example Name', 'email' => 'example@example.com', 'slot_id' => 12345), TRUE, TRUE); //=> www.supersaas.com/api/bookings/34554.json

#### Update Appointment/Booking

Update an appointment by `schedule_id` and `appointment_id` with appointment attributes, see the above link,
`update($schedule_id, $appointment_id, $attributes, $form=null, $webhook=null)`:

    SuperSaaS\Client::Instance()->appointments->update(12345, 67890, array('full_name' => 'New Name')); //=> array()

#### Delete Appointment/Booking

Delete a single appointment by `schedule_id` and `appointment_id`:

    SuperSaaS\Client::Instance()->appointments->delete(12345, 67890); //=> array()

#### Get Appointment/Booking

Get a single appointment by `schedule_id` and `appointment_id`:

    SuperSaaS\Client::Instance()->appointments->get(12345, 67890); //=> Appointment

#### List Appointments/Bookings

List appointments by `schedule_id`, with `form` and `start_time` and `limit` view params,
`getList($schedule_id, $form=null, $start_time=null, $limit=null)`:

    SuperSaaS\Client::Instance()->appointments->getList(12345, 67890, TRUE, TRUE); //=> array(Appointment, ...)

#### Get Agenda

Get agenda (upcoming) appointments by `schedule_id` and `user_id`, with `from_time` view param ([see](https://www.supersaas.com/info/dev/appointment_api#agenda),
`agenda($schedule_id, $user_id, $from_time = null, $slot=false)`:

    SuperSaaS\Client::Instance()->appointments->agenda(schedule_id=12345, user_id=67890, form=TRUE, slot=FALSE); //=> array(Appointment, ...)
    
    SuperSaaS\Client::Instance()->appointments->agenda(schedule_id=12345, user_id=67890, form=TRUE, slot=TRUE); //=> array(Slot, ...)

#### Get Available Appointments/Bookings

Get available appointments by `schedule_id`, with `from` time and `length_minutes` and `resource` params ([see](https://www.supersaas.com/info/dev/appointment_api#availability_api),
`available($schedule_id, $from_time = null, $length_minutes = null, $resource = null, $full = null, $limit = null)`:

    SuperSaaS\Client::Instance()->appointments->available(schedule_id=12345, from='2018-01-31 00:00:00', length_minutes=15, resource='My Class'); //=> array(Appointment, ...)

#### Get Recent Changes

Get recently changed appointments by `schedule_id`, with `from` time, `to` time, `user` user, `slot` view params (see [docs](https://www.supersaas.com/info/dev/appointment_api#recent_changes)),
`changes($schedule_id, $from_time = null, $to=null, $slot=false, $user=null, $limit=null, $offset=null)`:

    SuperSaaS\Client::Instance()->appointments->changes(schedule_id=12345, from_time='2018-01-31 00:00:00', slot=FALSE); //=> array(Appointment, ...)

    SuperSaaS\Client::Instance()->appointments->changes(schedule_id=12345, from_time='2018-01-31 00:00:00', slot=TRUE); //=> array(Slot, ...)

#### Get Recent Changes For Slots

Get recently changed appointments for slots by `schedule_id`, with `from_time` time param (see [docs](https://www.supersaas.com/info/dev/appointment_api#recent_changes)),
`changesSlots($schedule_id, $from_time = null)`:

    SuperSaaS\Client::Instance()->appointments->changesSlots(schedule_id=12345, from_time='2018-01-31 00:00:00'); //=> array(Slot, ...)

#### Get Agenda Slots

Get agenda (upcoming) slots by `schedule_id` and `user_id`, with `from_time` view param,
`agendaSlots($schedule_id, $user_id, $from_time = null)`:

    SuperSaaS\Client::Instance()->appointments->agendaSlots(12345, 67890, '2018-01-31 00:00:00') //=> array(Slot, ...)

_Note: only works for capacity type schedules._

#### Get list of appointments

Get list of appointments by `schedule_id`, with `today`, `from time`, `to` time and `slot` view param (see updated range function),
`listAppointments($schedule_id, $today = false, $from_time = null, $to = null, $slot = false)`:

    SuperSaaS\Client::Instance()->appointments->listAppointments(schedule_id=12345, today=TRUE, from_time='2020-01-31 00:00:00',from_time='2020-02-01 00:00:00' slot=False) //=> array(Slot, ...)

#### Get range of appointments

This is the updated method to fetch range (see above list) of appointments.
Get range of appointments by `schedule_id`, with `today`, `from` time, `to` time and `slot` view params (see [docs](https://www.supersaas.com/info/dev/appointment_api#range)),
`range($scheduleId, $today = false, $fromTime = null, $to = null, $slot = false, $user = null, $resourceId = null, $serviceId = null, $limit = null, $offset = null)`:

    SuperSaaS\Client::Instance()->appointments->range(12345, false, '2018-01-31 00:00:00', '2019-01-31 00:00:00', true) //=> array(Appointment, ...)

#### List Template Forms

Get all forms by template `superform_id`, with `from_time`, and `user` params ([see](https://www.supersaas.com/info/dev/form_api)):

    SuperSaaS\Client::Instance()->forms->getList(12345, '2018-01-31 00:00:00'); //=> array(Form, ...)

#### Get Form

Get a single form by `form_id`, will raise 404 error if not found:

    SuperSaaS\Client::Instance()->forms->get(12345); //=> Form

#### Get a list of SuperForms

Get a list of Form templates (SuperForms):

    SuperSaaS\Client::Instance()->forms->forms() //=> array(SuperForm, ...)

#### List Groups in an account

List Groups in an account ([see](https://www.supersaas.com/info/dev/information_api)):

    SuperSaaS\Client::Instance()->groups->list() //=> array(Group, ...)

#### List Promotions

Get a list of promotional coupon codes with pagination parameters `limit` and `offset` (see [docs](https://www.supersaas.com/info/dev/promotion_api)),
`list($limit = null, $offset = null)`:

    SuperSaaS\Client::Instance()->promotions->list() //=> array(Promotion, ...)

#### Get a single coupon code

Retrieve information about a single coupon code use with `promotion_code`:

    SuperSaaS\Client::Instance()->promotions->promotion((12345) //=> array(Promotion, ...)

#### Duplicate promotion code

Duplicate a template promotion by giving (new) `promotion_code` and `template_code` in that order,
`duplicatePromotionCode($promotionCode, $templateCode)`:

    Supersaas::Client.instance.promotions.duplicatePromotionCode(12345, 94832838)

## Examples

The ./examples folder contains several executable PHP scripts demonstrating how to use the API Client for common requests.

The examples will require your account name, api key, and some of the examples a schedule id and/or user id and/or form id. These can be set as environment variables. e.g.

    $ export SSS_API_UID=myuserid SSS_API_SCHEDULE=myscheduleid SSS_API_ACCOUNT_NAME=myaccountname  SSS_API_KEY=myapikey && php -f ./examples/appointments.php
    $ export SSS_API_FORM=myuserid SSS_API_ACCOUNT_NAME=myaccountname SSS_API_KEY=myapikey && php -f ./examples/forms.php
    $ export SSS_API_ACCOUNT_NAME=myaccountname && export SSS_API_KEY=myapikey && php -f ./examples/users.php

## Testing

The HTTP requests can be stubbed by configuring the client with the `dry_run` option, e.g.

    SuperSaaS\Client::Instance()->dry_run = TRUE;

Note, stubbed requests always return an empty array.

The `Client` also provides a `last_request` attribute containing the http array object from the last performed request, e.g. 

    SuperSaaS\Client::Instance()->last_request; //=> array('method' => ..., 'header' => ..., 'content' => ...)

The headers, body, etc. of the last request can be inspected for assertion in tests, or for troubleshooting failed API requests.

For additional troubleshooting, the client can be configured with the `verbose` option, which will `puts` any JSON contents in the request and response, e.g.

    SuperSaaS\Client::Instance()->verbose = TRUE;

## Run internal unit tests (phpunit)

    ./vendor/bin/phpunit # Runs all
    ./vendor/bin/phpunit --filter AppointmentsUnitTest # Run selection

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
