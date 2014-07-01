<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Commands\CommandInterface;

interface CommandStorageInterface
{
	public function attachCommand( CommandInterface $command, Array $info = array() );

	public function detachCommand( CommandInterface $command );

	public function getCommands();
}