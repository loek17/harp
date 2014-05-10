<?php

namespace CL\Luna\Test;

use CL\Luna\Model\Schema;
use CL\Luna\Field;
use CL\Luna\Rel;
use CL\Carpo\Assert;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class CitySchema extends Schema {

    private static $instance;

    /**
     * @return CitySchema
     */
    public static function get()
    {
        if (! self::$instance) {
            self::$instance = new CitySchema('CL\Luna\Test\City');
        }

        return self::$instance;
    }

    public function initialize()
    {
        $this
            ->setRels([
                new Rel\HasMany('users', $this, UserSchema::get()),
            ])
            ->setAsserts([
                new Assert\Present('location'),
            ])
            ->setFields([
                new Field\Integer('id'),
                new Field\String('zipCode'),
                new Field\String('location'),
            ]);
    }
}