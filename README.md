# HealthFoundation

The HealthCheckFoundation is an open source library that should make it easy to provide continuous health statuses for all important components in web projects.
It was designed to be very extensible.


[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Leankoala/HealthFoundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Leankoala/HealthFoundation/?branch=master)

## Using HealthFoundation

HealthFoundation was designed to be run standalone or within any project. 

### Example

#### Config file

This example checks if the disc space is used is less than 95 percent. 

```bash
$ php bin/health.php run health.yml
```

The config file ```health.yml``` could look like this

```yml
foundation:
  messages:
    success: "Storage server is up and running."
    failure: "Some problems occurred on storage server."

format:
  class: Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat

checks:
  spaceUsed:
    check: Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck
    identifier: space_used_check
    description: 'Space used on storage server'
    parameters:
      maxUsageInPercent: 95
```

#### Code

The same check as code

```php
# health.php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// max disc usage 95%
$spaceUsedCheck = new \Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck();
$spaceUsedCheck->init(95);

$foundation->registerCheck(
    $spaceUsedCheck, 
    'space_used_check', 
    'Space used on storage server');

$runResult = $foundation->runHealthCheck();

$formatter = new \Leankoala\HealthFoundation\Result\Format\Ietf\IetfFormat();
    
$formatter->handle(
    $runResult, 
    'Storage server is up and running.', 
    'Some problems occurred on storage server.'
);
```
### Checks

### Formatter

It is possible to produce any kind of health check format. At the moment the IETF standard ([Health Check Response Format for HTTP APIs](https://datatracker.ietf.org/doc/draft-inadarei-api-health-check/?include_text=1)) is supported 
but there is an simple interface that can be implemented to create new formats.

## Status

### Implemented health checks

As this is an open source project we want everybody to submit their own checks, that is why we provide the main author of every check in this list.

- Basic
  - Number
    - **LessThan** (nils.langner@leankoala.com) 
- Database
  - MySQL
    - Slave
      - **SlaveStatusField** (nils.langner@leankoala.com)
    -  **MysqlRunning** (nils.langner@leankoala.com)  
  - Redis
    - **ListLength** (nils.langner@leankoala.com)  
- Device  
  - **SpaceUsed** (nils.langner@leankoala.com)
  - **Uptime** (nils.langner@leankoala.com)
- Files
  - Content
    - **NumberOfLines** (nils.langner@leankoala.com)
  - **FileCreatedAfter** (nils.langner@leankoala.com)  
- Resource
  - HTTP
    - **StatusCode** (galenski@online-verlag-freiburg.de)  
- System
  - **Uptime** (nils.langner@leankoala.com)  
  - **NumberProcesses** (nils.langner@leankoala.com)  

### Ideas for health checks

- Database
  - MySQL
    - **NumberOfReturnedElements**
  - Redis
    - **isRunning**
- Files
  - **isWritable**  
  - **FileEditedAfter**    
- Tool
  - Wordpress
    - Plugins
      - **NumberOfOutdatedPlugins**
    - **isOutdated**
    - **isInsecure**    
    
    
## Outlook / Ideas

- **Suggestions** - the tool should find on its own what can be tested
- **Plugins** - It would be great if there where plugins/bundles for WordPress, Shopware, Symfony etc.    

