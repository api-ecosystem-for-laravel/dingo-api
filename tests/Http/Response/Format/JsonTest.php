<?php

namespace Dingo\Api\Tests\Http\Response\Format;

use Dingo\Api\Http\Response;
use Dingo\Api\Http\Response\Format\Json;
use Dingo\Api\Tests\BaseTestCase;
use Dingo\Api\Tests\Stubs\EloquentModelStub;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\MessageBag;

class JsonTest extends BaseTestCase
{
    public function setUp(): void
    {
        Response::setFormatters(['json' => new Json]);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        EloquentModelStub::$snakeAttributes = true;
    }

    /*
     * Read expected pretty printed JSON string from external file.
     *
     * JSON strings, that are expected for assertion in each test, are placed
     * in separate files to avoid littering tests and available on demand.
     * All the filenames are the same as the tests they associated to.
     *
     * @return string
     */
    private function getExpectedPrettyPrintedJson($testMethodName)
    {
        return require __DIR__.DIRECTORY_SEPARATOR.
            'ExpectedPrettyPrintedJson'.DIRECTORY_SEPARATOR.
            $testMethodName.'.json.php';
    }

    public function testMorphingEloquentModel()
    {
        $response = (new Response(new EloquentModelStub))->morph();

        $this->assertSame('{"foo_bar":{"foo":"bar"}}', $response->getContent());
    }

    public function testMorphingEloquentCollection()
    {
        $response = (new Response(new Collection([new EloquentModelStub, new EloquentModelStub])))->morph();

        $this->assertSame('{"foo_bars":[{"foo":"bar"},{"foo":"bar"}]}', $response->getContent());
    }

    public function testMorphLengthAwarePaginator()
    {
        $response = (new Response(new LengthAwarePaginator([new EloquentModelStub, new EloquentModelStub], 2, 10)))->morph();

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('total', $content);
        $this->assertSame(2, $content['total']);
    }

    public function testMorphSimplePaginator()
    {
        $response = (new Response(new Paginator([new EloquentModelStub, new EloquentModelStub], 2, 1)))->morph();

        $content = json_decode($response->getContent(), true);

        $this->assertArrayNotHasKey('total', $content);
    }

    public function testMorphingEmptyEloquentCollection()
    {
        $response = (new Response(new Collection))->morph();

        $this->assertSame('[]', $response->getContent());
    }

    public function testMorphingString()
    {
        $response = (new Response('foo'))->morph();

        $this->assertSame('foo', $response->getContent());
    }

    public function testMorphingArray()
    {
        $messages = new MessageBag(['foo' => 'bar']);

        $response = (new Response(['foo' => 'bar', 'baz' => $messages]))->morph();

        $this->assertSame('{"foo":"bar","baz":{"foo":["bar"]}}', $response->getContent());
    }

    public function testMorphingUnknownType()
    {
        $this->assertSame('1', (new Response(1))->morph()->getContent());
    }

    public function testMorphingEloquentModelWithCamelCasing()
    {
        EloquentModelStub::$snakeAttributes = false;

        $response = (new Response(new EloquentModelStub))->morph();

        $this->assertSame('{"fooBar":{"foo":"bar"}}', $response->getContent());
    }

    public function testMorphingEloquentCollectionWithCamelCasing()
    {
        EloquentModelStub::$snakeAttributes = false;

        $response = (new Response(new Collection([new EloquentModelStub, new EloquentModelStub])))->morph();

        $this->assertSame('{"fooBars":[{"foo":"bar"},{"foo":"bar"}]}', $response->getContent());
    }

    public function testMorphingArrayWithTwoSpacesPrettyPrintIndent()
    {
        $options = [
            'json' => [
                'pretty_print' => true,
                'indent_style' => 'space',
                'indent_size' => 2,
            ],
        ];

        Response::setFormatsOptions($options);

        $array = ['foo' => 'bar', 'baz' => ['foobar' => [42, 0.00042, '', null]]];

        $response = (new Response($array))->morph();

        $this->assertSame($this->getExpectedPrettyPrintedJson(__FUNCTION__), $response->getContent());
    }

    public function testMorphingArrayWithFourSpacesPrettyPrintIndent()
    {
        $options = [
            'json' => [
                'pretty_print' => true,
                'indent_style' => 'space',
                'indent_size' => 4,
            ],
        ];

        Response::setFormatsOptions($options);

        $array = ['foo' => 'bar', 'baz' => ['foobar' => [42, 0.00042, '', null]]];

        $response = (new Response($array))->morph();

        $this->assertSame($this->getExpectedPrettyPrintedJson(__FUNCTION__), $response->getContent());
    }

    public function testMorphingArrayWithEightSpacesPrettyPrintIndent()
    {
        $options = [
            'json' => [
                'pretty_print' => true,
                'indent_style' => 'space',
                'indent_size' => 8,
            ],
        ];

        Response::setFormatsOptions($options);

        $array = ['foo' => 'bar', 'baz' => ['foobar' => [42, 0.00042, '', null]]];

        $response = (new Response($array))->morph();

        $this->assertSame($this->getExpectedPrettyPrintedJson(__FUNCTION__), $response->getContent());
    }

    public function testMorphingArrayWithOneTabPrettyPrintIndent()
    {
        $options = [
            'json' => [
                'pretty_print' => true,
                'indent_style' => 'tab',
            ],
        ];

        Response::setFormatsOptions($options);

        $array = ['foo' => 'bar', 'baz' => ['foobar' => [42, 0.00042, '', null]]];
        $response = (new Response($array))->morph();

        $this->assertSame($this->getExpectedPrettyPrintedJson(__FUNCTION__), $response->getContent());
    }
}
