<?php namespace CL\Luna\Event;

use CL\Luna\Model\Model;
/**
 * @author     Ivan Kerin
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://www.opensource.org/licenses/isc-license.txt
 */
class ModelEvent extends Event
{
	const SAVE = 1;
	const DELETE = 3;
	const VALIDATE = 4;
	const PRESERVE = 5;

	public function __construct($type, Model $target)
	{
		parent::__construct($type, $target);
	}

}
