<?php

use App\System\LinkGrabber;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LinkGrabberTest extends TestCase
{

    /** @var  LinkGrabber */
    private $linkGrabber;

    protected function setUp()
    {
        $this->linkGrabber = new LinkGrabber();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGrabLinks()
    {
        $html = file_get_contents(__DIR__ . '/../storage/test/page.html');
        $links = $this->linkGrabber->grabLinks($html);
        $this->assertTrue(is_array($links));
    }


}
