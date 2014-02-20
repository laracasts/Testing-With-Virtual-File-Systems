<?php

namespace spec\Acme;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use org\bovigo\vfs\VfsStream;
use org\bovigo\vfs\VfsStreamWrapper;

class FilesystemSpec extends ObjectBehavior {
    function let()
    {
        VfsStream::setup('root_dir', null, [
            'foo.txt' => 'foobar'
        ]);

        $this->beConstructedWith(VfsStream::url('root_dir'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Acme\Filesystem');
    }

    function it_determines_whether_a_given_file_exists()
    {
        $this->exists('foo.txt')->shouldBe(true);

        unlink(VfsStream::url('root_dir/foo.txt'));

        $this->exists('foo.txt')->shouldBe(false);
    }

    function it_fetches_a_file()
    {
        $this->get('foo.txt')->shouldReturn('foobar');
    }

    function it_replaces_the_contents_of_a_file_with_a_provided_string()
    {
        $this->put('foo.txt', 'laracasts');

        $this->get('foo.txt')->shouldReturn('laracasts');
    }

    function it_appends_to_a_file()
    {
        $this->append('foo.txt', 'new text');

        $this->get('foo.txt')->shouldMatch('/foobarnew text/');
    }

    function it_deletes_a_file()
    {
        $this->delete('foo.txt')->shouldDeleteFile('foo.txt');

        // Or:
        // $this->shouldThrow('Acme\FileDoesNotExist')->duringGet('foo.txt');
    }

    public function getMatchers()
    {
        return [
            'deleteFile' => function($actual, $file)
            {
                return ! VfsStreamWrapper::getRoot()->hasChild($file);
            }
        ];
    }

}

