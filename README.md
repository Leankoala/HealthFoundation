# HealthFoundation

The HealthCheckFoundation is an open source library that should make it easy to provide continuous health statuses for all important components in web projects.
It was designed to be very extensible.


[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Leankoala/HealthFoundation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Leankoala/HealthFoundation/?branch=master)

## Using HealthFoundation

HealthFoundation was designed to be run standalone or within any project. 

### Example

```php
# health.php

include_once __DIR__ . '/../vendor/autoload.php';

$foundation = new \Leankoala\HealthFoundation\HealthFoundation();

// max disc usage 95%
$spaceUsedCheck = new \Leankoala\HealthFoundation\Check\Device\SpaceUsedCheck();
$spaceUsedCheck->init(95);

$foundation->registerCheck($spaceUsedCheck);

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

It is possible to produce any kind of health check format. At the moment the IETF standard ([Health Check Response Format for HTTP APIs](https://tools.ietf.org/id/draft-inadarei-api-health-check-01.html)) is supported 
but there is an simple interface that can be implemented to create new formats.

## Status

### Implemented health checks

- Database
  - MySQL
    - Slave
      - **SlaveStatusField** (nils.langner@leankoala.com)
      
- Device  
  - **SpaceUsed** (nils.langner@leankoala.com)
- Files
  - Content
    - **NumberOfLines** (nils.langner@leankoala.com)
  - **FileCreatedAfter** (nils.langner@leankoala.com)  

### Ideas for health checks

- Redis
  -  Queue Length
  
  