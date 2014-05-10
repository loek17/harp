<?php

namespace CL\Luna\Test\Model;

use CL\Luna\Test\Store\BlogPostStore;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class BlogPost extends Post {

    public function getStore()
    {
        return BlogPostStore::get();
    }

    public $isPublished = false;
}