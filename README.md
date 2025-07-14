# Symfony Functional Test

## Install
```` bash
$ composer require php-solution/sf-functional-test
````

## Load environment variables from files
Add to your phpunit.xml extension and configure paths (comma separated relative file paths from your phpunit.xml configuration file):
````XML 
<bootstrap class="PhpSolution\FunctionalTest\PhpUnit\Extension\PreRunEnvLoaderExtension">
    <parameter name="paths" value="../.env,.env"/>
</bootstrap>
````

## Load Doctrine fixtures before test cases 
Add to your phpunit.xml extension:
````XML
<bootstrap class="PhpSolution\FunctionalTest\PhpUnit\Extension\PreRunCommandLauncherExtension">
     <parameter name="command" value="functional-test:fixtures:load"/>
    <!--Default is false. If true, if command's exit code > 0 then tests will fail immediately-->
    <parameter name="exitOnError" value="true" />
</bootstrap>
````

## Run Doctrine migrations before test cases 
Add to your phpunit.xml extension:
````XML
<bootstrap class="\PhpSolution\FunctionalTest\PhpUnit\Extension\DoctrineMigrationExtension" />
````
Or simply:
````XML
<bootstrap class="PhpSolution\FunctionalTest\PhpUnit\Extension\PreRunCommandLauncherExtension">
    <parameter name="command" value="doctrine:migration:migrate --no-interaction"/>
    <parameter name="exitOnError" value="true" />
</bootstrap>
````

## Run sf command with parameters
Add to your phpunit.xml extension:
````XML
<bootstrap class="PhpSolution\FunctionalTest\PhpUnit\Extension\PreRunCommandLauncherExtension">
    <parameter name="command" value="doctrine:mongodb:schema:drop --collection"/>
</bootstrap>
````

## Run native command with parameters
Add to your phpunit.xml extension:
````XML
<bootstrap class="PhpSolution\FunctionalTest\PhpUnit\Extension\PreRunNativeCommandLauncherExtension">
    <parameter name="command" value="bin/console doctrine:mongodb:schema:drop --collection"/>
</bootstrap>
````
    
## Using Test case additional functionallity PhpSolution\FunctionalTest\TestCase\AppTestCase   
### Using Authorization:
1) Add to your config_test.yml:
````yaml
security:
    firewalls:
        your_secured_category:
            http_basic: ~
````
2)  Use on TestCase
````php
$client = $this->getAuthorizedClient('user_login', 'password');
````

### Use profiler for testing
1) Add to your framework.yml:
````yaml
when@test:
  framework:
    profiler:
      collect: false
````

2) Add ProfilerTrait to your TestCase
````php
use PhpSolution\FunctionalTest\TestCase\ProfilerTrait;
````

3) Use the following methods to run request with profiler:
````php
[$response, $profiler] = self::withRequestProfiler(static function () {
    return self::createSystemAuthorizedTester()
        ->setExpectedStatusCode(Response::HTTP_OK)
        ->sendGet('/some/awesome/endpoint', ['foo' => 'bar']);
});
````
By default, the following collectors are enabled: 'db', 'http_client', 'cache', 'memory'
but you can always disable or enable new collectors by passing them in the `withRequestProfiler` method:
````php
[$response, $profiler] = self::withRequestProfiler(static function () {
    return self::createSystemAuthorizedTester()
        ->setExpectedStatusCode(Response::HTTP_OK)
        ->sendGet('/some/awesome/endpoint', ['foo' => 'bar']);
}, ['db', 'http_client']);
````

4) Use profiler to get collectors:
````php
self::getCollector($profiler, 'http_client');
self::getDoctrineCollector($profiler); // returns DoctrineCollector
````

### Work with Doctrine (ORM, ODM)
1. Add EntityTrait or DocumentTrait to your TestCase

````php
$this->getDoctrine()
````  
3. Find Entity helper method:
````php
protected function findEntity(string $entityClass, string $orderBy = 'id', array $findBy = [])
protected function findDocument(string $documentClass, array $criteria = [])
protected function findDocuments(string $documentClass, array $criteria = [], array $orderBy = [])
````
   
4. Refresh Entity:
````php
protected function refreshEntity($entity) 
protected function refreshDocument($document)
````

#### Assert doctrine queries using request profiler

1. Make sure you have setup the profiler as described above.
2. Assert queries using profiler:
````php
self::assertDoctrineQueriesCount(3, $profiler);
self::assertDoctrineQueriesCountLessThanOrEqual(3, $profiler);
self::assertDoctrineSelectQueriesCountLessThanOrEqual(2, $profiler);

// or even debug queries itself

self::getDoctrineCollector($profiler)->getQueries(); // returns array of executed queries
````

### Test emails

1. Add config
```yaml
swiftmailer:
    disable_delivery: true
    spool:
        type: file
        path: '%kernel.project_dir%/var/spool'
    delivery_addresses: ~
```

2. Add SpoolTrait and find methods
````php
public function purgeSpool()
public function getSpooledEmails()
public function getEmailContent($file)
protected function getSpoolDir()
````

## Example of correct project structure:
See correct project structure and configs for functional tests on [link](/examples/project-structure/)
