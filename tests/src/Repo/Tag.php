<?php

namespace CL\Luna\Test\Repo;

use CL\Luna\Model\AbstractRepo;
use CL\Luna\Field;
use CL\Luna\Rel;
use CL\Carpo\Assert;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Tag extends AbstractRepo {

    private static $instance;

    /**
     * @return TagRepo
     */
    public static function get()
    {
        if (! self::$instance) {
            self::$instance = new Tag('CL\Luna\Test\Model\Tag');
        }

        return self::$instance;
    }

    public function initialize()
    {
        $this
            ->setFields([
                new Field\Integer('id'),
                new Field\String('name'),
            ])
            ->setRels([
                new Rel\HasMany('postTags', $this, PostTag::get()),
                new Rel\HasManyThrough('posts', $this, Post::get(), 'postTags'),
            ])
            ->setAsserts([
                new Assert\Present('name'),
            ]);
    }

}
