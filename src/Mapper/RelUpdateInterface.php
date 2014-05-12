<?php

namespace CL\Luna\Mapper;

/*
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
interface RelUpdateInterface
{
    public function update(AbstractNode $node, AbstractLink $link);
}
