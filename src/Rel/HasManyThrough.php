<?php

namespace CL\Luna\Rel;

use CL\Util\Arr;
use CL\Util\Objects;
use CL\Luna\AbstractDbRepo;
use CL\LunaCore\Model\AbstractModel;
use CL\LunaCore\Model\Models;
use CL\LunaCore\Repo\LinkMany;
use CL\LunaCore\Rel\AbstractRelMany;
use CL\LunaCore\Rel\DeleteManyInterface;
use CL\LunaCore\Rel\InsertManyInterface;
use CL\Atlas\Query\AbstractQuery;

/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class HasManyThrough extends AbstractRelMany implements DbRelInterface, DeleteManyInterface, InsertManyInterface
{
    protected $foreignKey;
    protected $through;

    public function __construct($name, AbstractDbRepo $repo, AbstractDbRepo $foreignRepo, $through, array $options = array())
    {
        $this->through = $through;
        $this->foreignKey = lcfirst($foreignRepo->getName()).'Id';
        $this->key = lcfirst($repo->getName()).'Id';

        parent::__construct($name, $repo, $foreignRepo, $options);
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    public function getThroughRel()
    {
        return $this->getRepo()->getRel($this->through);
    }

    public function getThroughRepo()
    {
        return $this->getThroughRel()->getForeignRepo();
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getThroughKey()
    {
        return $this->getName().'Key';
    }

    public function hasForeign(Models $models)
    {
        $keys = $models->getIds();

        return ! empty($keys);
    }

    public function getThroughTable()
    {
        return $this->getThroughRel()->getName();
    }

    public function loadForeign(Models $models, $flags = null)
    {
        $throughKey = $this->getThroughTable().'.'.$this->getThroughRel()->getForeignKey();
        $throughForeignKey = $this->getThroughTable().'.'.$this->key;
        $repo = $this->getForeignRepo();

        $keys = $models->getIds();

        $select = $repo->findAll()
            ->column($throughKey, $this->getTHroughKey())
            ->joinRels([$this->through])
            ->whereIn($throughForeignKey, $keys);

        return $select->loadRaw($flags);
    }

    public function areLinked(AbstractModel $model, AbstractModel $foreign)
    {
        return $model->getId() == $foreign->{$this->getThroughKey()};
    }

    public function join(AbstractQuery $query, $parent)
    {
        $alias = $this->getName();
        $condition = "ON $alias.{$this->getForeignRepo()->getPrimaryKey()} = {$this->through}.{$this->getForeignKey()}";

        if ($this->getForeignRepo()->getSoftDelete()) {
            $condition .= " AND $alias.deletedAt IS NULL";
        }

        $this->getThroughRel()->join($query, $parent);

        $query
            ->joinAliased($this->getForeignRepo()->getTable(), $alias, $condition);
    }

    public function delete(AbstractModel $model, LinkMany $link)
    {
        $removed = new Models();
        $through = $this->getRepo()->loadLink($model, $this->through);

        foreach ($link->getRemoved() as $removed) {
            foreach ($through as $item) {
                if ($item->{$this->foreignKey} == $removed->getId()) {
                    $item->delete();
                    $removed->add($item);
                }
            }
        }

        return $removed;
    }

    public function insert(AbstractModel $model, LinkMany $link)
    {
        $inserted = new Models();

        if (count($link->getAdded()) > 0) {
            $through = $this->getRepo()->loadLink($model, $this->through);

            foreach ($link->getAdded() as $added) {
                $item = $this->getThroughRepo()->newModel([
                    $this->getThroughRel()->getForeignKey() => $model->getId(),
                    $this->foreignKey => $added->getId(),
                ]);

                $through->add($item);

                $inserted->add($item);
            }
        }

        return $inserted;
    }
}
