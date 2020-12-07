<?php

namespace App\Tests\Services;

use App\Services\Slugger;
use PHPUnit\Framework\TestCase;
class SluggerTest extends TestCase
{
    public function testSlug()
    {
        $sample = "À s'abîmer dans le cœur d'albâtre de cette marâtre, notre regard hélas s'égare.";
        $expected = "A s'abimer dans le coeur d'albatre de cette maratre, notre regard helas s'egare.";

        $slugger = new Slugger();
        $result = $slugger->slug($sample);

        $this->assertEquals($result, $expected);
    }
}