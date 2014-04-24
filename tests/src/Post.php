<?php namespace CL\Luna\Test;

use CL\Luna\Model\Model;
use CL\Luna\Model\Schema;
use CL\Luna\Model\SchemaTrait;
use CL\Luna\Field;
use CL\Luna\Rel;
use CL\Carpo\Assert;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class Post extends Model {

    use SchemaTrait;

    public $id;
    public $title;
    public $body;
    public $price;
    public $tags;
    public $createdAt;
    public $updatedAt;
    public $publishedAt;
    public $userId;
    public $polymorphicClass;

    public function getUser()
    {
        return $this->loadRelLink('user')->get();
    }

    public function getTags()
    {
        return $this->loadRelLink('tags');
    }

    public function getPostTags()
    {
        return $this->loadRelLink('postTags');
    }

    public function setUser(User $user)
    {
        return $this->loadRelLink('user')->set($user);
    }

    public static function initialize(Schema $schema)
    {
        $schema
            ->setPolymorphic(true);

        $schema
            ->setRels([
                new Rel\BelongsTo('user', $schema, User::getSchema()),
                new Rel\HasMany('postTags', $schema, PostTag::getSchema()),
                new Rel\HasManyThrough('tags', $schema, Tag::getSchema(), 'postTags'),
            ]);

        $schema
            ->setFields([
                new Field\Integer('id'),
                new Field\String('title'),
                new Field\Text('body'),
                new Field\Decimal('price'),
                new Field\Serialized('tags', Field\Serialized::CSV),
                new Field\Timestamp('createdAt'),
                new Field\Timestamp('updatedAt'),
                new Field\DateTime('publishedAt'),
                new Field\Integer('userId'),
                new Field\String('polymorphicClass'),
            ]);

        $schema
            ->setAsserts([
                new Assert\Present('title'),
            ]);
    }

}
