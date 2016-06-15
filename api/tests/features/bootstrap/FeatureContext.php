<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var \Laravel\Lumen\Application
     */
    var $app;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $requestPayload;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $responsePayload;

    /**
     * @var string
     */
    protected $scope;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($baseUrl, array $config = [])
    {
        $config['base_uri'] = $baseUrl;

        $this->client = new Client($config);

        $this->app = require __DIR__ . '/../../../bootstrap/app.php';
    }

    /**
     * @Given the database has records
     */
    public function theDatabaseHasRecords()
    {
        $artisan = $this->app[\Illuminate\Contracts\Console\Kernel::class];

        $artisan->call('migrate');
        $artisan->call('db:seed');
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $payload)
    {
        $this->requestPayload = (string) $payload;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $resource)
    {
        $this->resource = $resource;

        $method = strtolower($httpMethod);

        try {
            switch ($httpMethod) {
                case 'PUT':
                case 'POST':
                    $this->response = $this->client->$method($resource, ['body' => $this->requestPayload]);
                    break;
                default:
                    $this->response = $this->client->$method($resource);
            }
        } catch (BadResponseException $e) {
            $response = $e->getResponse();

            if ($response === null) {
                throw $e;
            }

            $this->response = $response;
        }
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        $response = $this->getResponse();
        $contentType = $response->getHeader('Content-Type')[0];

        if ($contentType === 'application/json') {
            $bodyOutput = $response->getBody();
        } else {
            $bodyOutput = 'Output is ' . $contentType . ', which is not JSON and is therefore scary. Run the request manually.';
        }

        PHPUnit_Framework_Assert::assertSame((int) $statusCode, (int) $this->getResponse()->getStatusCode(), $bodyOutput);
    }

    /**
     * @Then the :property property exists
     */
    public function thePropertyExists($property)
    {
        $payload = $this->getScopePayload();

        $message = sprintf(
            'Asserting the [%s] property exists in the scope [%s]: %s',
            $property,
            $this->scope,
            json_encode($payload)
        );

        if (is_object($payload)) {
            PHPUnit_Framework_Assert::assertTrue(array_key_exists($property, get_object_vars($payload)), $message);
        } else {
            PHPUnit_Framework_Assert::assertTrue(array_key_exists($property, $payload), $message);
        }
    }

    /**
     * @Then the properties exist:
     */
    public function thePropertiesExist(PyStringNode $properties)
    {
        foreach ($properties->getStrings() as $property) {
            $this->thePropertyExists($property);
        }
    }

    /**
     * @Then the :property property equals :expectedValue
     */
    public function thePropertyEquals($property, $expectedValue)
    {
        $payload = $this->getScopePayload();
        $actualValue = $this->arrayGet($payload, $property);

        PHPUnit_Framework_Assert::assertEquals(
            $actualValue,
            $expectedValue,
            sprintf(
                'Asserting the [%s] property in current scope equals [%s]: %s',
                $property,
                $expectedValue,
                json_encode($payload)
            )
        );
    }

    /**
     * @Then the :property property is an integer
     */
    public function thePropertyIsAnInteger($property)
    {
        $payload = $this->getScopePayload();

        PHPUnit_Framework_Assert::assertInternalType(
            'int',
            $this->arrayGet($payload, $property),
            "Asserting the [$property] property in current scope [{$this->scope}] is an integer: ".json_encode($payload)
        );
    }

    /**
     * @Then scope into the first :scope property
     */
    public function scopeIntoTheFirstProperty($scope)
    {
        $this->scope = sprintf("%s.0", $scope);
    }

    /**
     * Checks the response exists and returns it.
     *
     * @return Response
     */
    protected function getResponse()
    {
        if (!$this->response) {
            throw new Exception("You must first make a request to check a response.");
        }

        return $this->response;
    }

    /**
     * Return the response payload from the current response.
     *
     * @return  mixed
     */
    protected function getResponsePayload()
    {
        if (! $this->responsePayload) {
            $json = json_decode($this->getResponse()->getBody(true));
            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Failed to decode JSON body ';
                switch (json_last_error()) {
                    case JSON_ERROR_DEPTH:
                        $message .= '(Maximum stack depth exceeded).';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $message .= '(Underflow or the modes mismatch).';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $message .= '(Unexpected control character found).';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $message .= '(Syntax error, malformed JSON).';
                        break;
                    case JSON_ERROR_UTF8:
                        $message .= '(Malformed UTF-8 characters, possibly incorrectly encoded).';
                        break;
                    default:
                        $message .= '(Unknown error).';
                        break;
                }
                throw new Exception($message);
            }
            $this->responsePayload = $json;
        }
        return $this->responsePayload;
    }
    /**
     * Returns the payload from the current scope within
     * the response.
     *
     * @return mixed
     */
    protected function getScopePayload()
    {
        $payload = $this->getResponsePayload();
        if (! $this->scope) {
            return $payload;
        }
        return $this->arrayGet($payload, $this->scope);
    }
    /**
     * Get an item from an array using "dot" notation.
     *
     * @copyright   Taylor Otwell
     * @link        http://laravel.com/docs/helpers
     * @param       array   $array
     * @param       string  $key
     * @param       mixed   $default
     * @return      mixed
     */
    protected function arrayGet($array, $key)
    {
        if (is_null($key)) {
            return $array;
        }
        // if (isset($array[$key])) {
        //     return $array[$key];
        // }
        foreach (explode('.', $key) as $segment) {
            if (is_object($array)) {
                if (! isset($array->{$segment})) {
                    return;
                }
                $array = $array->{$segment};
            } elseif (is_array($array)) {
                if (! array_key_exists($segment, $array)) {
                    return;
                }
                $array = $array[$segment];
            }
        }
        return $array;
    }
}
