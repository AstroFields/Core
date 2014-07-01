# wecodemore - AstroFields

Form fields on <strike>steroids</strike> asteroids.

# Intro

While this framework might be written by Pattern Astronauts, you don't have to be one
to use it. Welcome to the orbit of the most flexible WordPress field framework.

## Prerequisites

You server needs PHP 5.3+ and WordPress. That's it. No rocket science.

# Mediator

The `Field` class requires a `key`, which is used as `name` and `id` attribute.
Additionally it should be used as key when CRUD operations are executed.

It is a `SplSubject` and mediator class for `Observer`s and `Command`s.
It keeps both in `SplObjectstorage` containers and makes them accessible through
those Storage objects.

To attach an `Observer` use `attach( $observer )`.

To attach a command use `attachCommand( $command, array( 'data' ) )` on a `Field` instance.

	$field = new Field(
		$key,
		array( 'types' )
	);
	$field
		->attach( $observer );
		->attachCommand( 'SanitizeEmail', array(
			'blacklist' => array(
				'10minutemail.com',
				'blackhatmail.com',
			),
		) );

Per default, the `ObserverStorage` holds all Observers
and each Observer has `key` and `types` attached.

The `CommandStorage` holds all Commands, has the `key` and `types` attached.
Every Command also has all its input arguments attached.

> **Rule:** A `Field` does not know about what data it handles, how and when commands
are executed, of what type it is or how it looks like. It just knows that it has a key,
a set of types and a bunch of commands and observers attached.

# Observers

Observers are groups of similar commands.

Per default every `Observer` is an instance of `SplObserver` and receives
the whole `Field` as `SplSubject` inside the required `update()` method.

## How to use an Observer

Every Observer receives the whole `SplSubject`, it has access to all commands via the
`CommandStorage`. As an Observer groups and invokes commands, it is necessary to filter
out those commands that are not wanted for the observer. To achieve this, you need to
define the `filter( $storage )` method. It should receive only the `CommandStorage` as
value and do nothing than returning the currently needed Commands.

	$storage = $subject->getCommands();
	$storage = $this->filter( $storage );

It should return the `$storage` with the unwanted commands `detach()`ed.

When commands are filtered, they can be processed in a simple loop. Inside the loop,
the command should be attached to a hook or filter.

> **Rule:** Only an Observer knows the context (hook/filter) of a command.

Inside the loop, you attach the commands to a hook or filter.

	foreach ( $storage as $command )
	{
		add_action( 'sanitize_post_meta_foo', array( $storage->current, 'execute' ), 20, 3 );
	}

> **Rule:** An Observer knows **where** to execute a command. But only a filter
can tell the Observer which types of commands he has to handle.

Hint: If you need access to the `key` or `types`, you can use `$storage->getInfo()` inside the
loop. This allows easy access to the data for the purpose of generating contextual hook names.
Example:

	foreach ( $storage as $command )
	{
	    // Sanitize the value of an `address` meta field for the `venue` post type
	    // sanitize_venue_meta_address
	    // sanitize_{$post_type}_meta_{$key}
	    $info = $storage->getInfo();
	    foreach ( $info['types'] as $type )
	    {
			add_action(
				"sanitize_{$type}_meta_{$info['key'}",
				array( $storage->current, 'execute' ),
				20, 3
			);
		}
	}

## How to use a `FilterIterator` inside the Observer

In theory the `filter()` method should be nothing than a wrapper for an actual `FilterIterator`
(or any other `Iterator` used to detach commands from the storage). In the real world,
you can detach commands in the method directly, but we don't recommend this.

	/**
	 * Detach unwanted commands from the CommandStorage
	 * @param \SplObjectStorage $storage
	 * @return \Traversable
	 */
	public function filter( \SplObjectStorage $storage )
	{
		$storage = new DetachFilterIterator( $storage );
		$storage->rewind();

		return $storage;
	}

Here is an example of a `FilterIterator` that detaches commands. We use interfaces that
implement on related commands. This makes detaching/filtering out commands extremely
easy and convenient.

	<?php
	namespace WCM\Meta\Iterators;

	use WCM\Meta\Commands\SanitizeInterface;

	/**
	 * Class SanitzeFilterIterator
	 * @package WCM\Meta\Iterators
	 */
	class SanitzeFilterIterator extends \FilterIterator
	{
		/**
		 * Check whether the current element of the iterator is acceptable
		 * Runs prior to current() and in case detaches from the Objectstorage
		 * @return bool true if the current element is an instanceof SanitizeInterface
		 */
		public function accept()
		{
			if ( ! $this->current() instanceof SanitizeInterface )
				$this->getInnerIterator()->detach( $this->current() );

			return $this->current() instanceof SanitizeInterface;
		}
	}

As the `Field` Mediator `getCommands()` method actually returns a `clone` of the `CommandStorage`,
you can safely detach commands without conflicting with any other Observer.

As the currently iterated Element is an `instanceof` a Command, we can use it to refer
to commands stored in the `CommandStorage`. This makes detaching them extremely easy
and the `CommandStorage`, an `SplObserver` and a `FilterIterator` together very powerful
without the need to write tons of lines just to target a set of commands safely.

# Commands

Commands can be seen as tasks, invoked by an Observer. They don't know in which context/where
they are executed.

> **Rule:** A command by itself needs nothing than to receive and handle (or react on) input data.

This makes Commands reusable in a lot of different scenarios.

Here's an example of a very simple and reusable interface that is used to sanitze URls.

	<?php

	namespace WCM\Meta\Commands;

	use WCM\Meta\Commands\CommandInterface,
		WCM\Meta\Commands\SanitizeInterface;

	class SanitizeURl implements CommandInterface, SanitizeInterface
	{
		/**
		 * Returns only valid URls
		 * @param  string $value
		 * @return string|NULL
		 */
		public function execute( $value = NULL )
		{
			return filter_var(
				$value,
				FILTER_VALIDATE_URL,
				[ 'flags' => FILTER_NULL_ON_FAILURE ]
			);
		}
	}

The interface itself doesn't even define the amount or type of values for
the concrete implementation. This is the responsibility of each separate command itself.
This way Commands implementing `CommandInterface` can be used in any context, on any
filter or hook, no matter how many arguments it provides.

> **Rule:** Always set a default value (or `NULL`) for your commands arguments. Else
you will hit errors and warnings.

A command would not be much by itself, therefore we have implemented `Receivers` and `Views`.
More about that later on.

## How to use Commands with Observers

To offer a common entry point for Observers, we have the `CommandInterface`. It requires
nothing than a method called `execute()`, which then can be called by the Observer when
looping through Commands. Of course you don't have to use that interface, but we highly
recommend that.

If you want to start over small, you can simply add a callback/action to an Observer and
hook that instead of using a real command.

## How to pass data to Commands

When you build a command, you don't want it to know what sort of data it processes
when executing. Imagine a command displaying an Admin Notice: you don't want it to
know what string it should display to the user as you want to display different strings
using the same command. Or when saving a meta value for a post type, you don't want the
command to know which key it should look for when retrieving the data. And as in most cases…

> **Rule:** There's an interface for that.

When your Command implements the `DataAwareInterface`, it has to define a method `setData( $data )`.

	class FooCommand implements CommandInterface, DataAwareInterface
	{
		private $data;

		public function setData( Array $data )
		{
			$this->data = $data;
		}

		public function execute() { ... }
	}

Use this method from within your Observers `update()` method when looping through commands.

	foreach ( $storage as $command )
	{
		$info = $storage->getInfo();
		$storage->current()->setData( $info );
	}

It's as simple as that. The data array that you added when attaching the Command to the Mediator
now is accessible in your Command. Later on, in your Commands `execute()` method,
you can access this data safely.

	public function execute()
	{
		$data = $this->data;
		// do something with it.
	}

## How to pass data from a Command somewhere else: Receivers

Commands are just the main entry point. In some cases you want to push the processed
data somewhere else, deliver feedback or chain another command to it. This is where
you want to use a `Receiver`. Simply let your Command implement the `ReceiverAwareInterface`.
Then add the `setReceiver()` method.

	class FooCommand implements CommandInterface, ReceiverAwareInterface
	{
		private $receiver;

		public function setReceiver( ReceiverInterface $receiver )
		{
			$this->receiver = $receiver;
		}
	}

A Receiver needs to implement the `ReceiverInterface` and therefore the `process( $data )`
method.

	class FooReceiver implements ReceiverInterface
	{
		private $command;

		public function setCommand( CommandInterface $command )
		{
			$this->command = $command;
		}

		public function process( $data )
		{
			$this->command->execute( $data );
		}
	}

Now you have a chain of commands.

> **Rule:** In chains of commands, each Command does not need to know of each other.


## How to attach a View (or any other Receiver) to a Command

When you want to display something from a command, you might want to attach a View. While
you could easily just use a callback in your Observer (bad) or Command (slightly better,
but still bad), we (again) got an interface for that.

The `ViewAwareInterface` requires you to implement a `setView( $view )` method in your class.
When you use this in combination with a `ReceiverInterface`, then you end up with a very
easy and convenient way to communicate with the user. Take a look at the `ViewableReceiver`
for an example. (Probably you won't need another class than this adapter/receiver to attach views).

	class ViewableReceiver implements ReceiverInterface, ViewAwareInterface
	{
		private $view;

		public function setView( ViewableInterface $view )
		{
			$this->view = $view;
		}

		public function process( $data )
		{
			$this->view->display( $data );
		}
	}

Attaching Data, Receivers and Views to Commands is the responsibility of an Observer.
In the following example, we first attach data to a Command, then add a View and finally
execute it on the given hook or action.

	public function update( \SplSubject $subject )
	{
		$storage = $subject->getCommands();
		$storage = $this->filter( $storage );

		foreach ( $storage as $command )
		{
			// Attach data
			$info = $storage->getInfo();
			$storage->current()->setData( $info );

			// Attach view
			$view = new ViewableReceiver;
			$view->setView( new AdminNoticeView );
			$storage->current()->setReceiver( $view );

			// Build context (hook/filter names) from the current Command info/data
			$context = $this->getContext( $info );

			foreach ( $context as $c )
				add_action( $c, [ $storage->current(), 'execute' ], 10, 3 );
		}
	}

The command has data and a Receiver attached. In this scenario, the Receiver is a View
to display something to the user.

The `AdminNoticeView` finally has no other job than to retrieve already prepared data
and display it.

	class AdminNoticeView implements ViewableInterface
	{
		public function display( $data )
		{
			printf( '<div class="%s fade"><p>%s</p></div>',
				'updated',
				$data
			);
		}
	}

# Sketch

Command:
	Display Input Field
Data:
	* name/id
	* value

Command:
	Display Select/Dropdown
Data:
	* name/id
	* selected/value
	* options [ name/id : label String ]

Command:
	Display Label
Data:
	* for
	* label String

Command:
	Save Post Type Meta
Data:
	* name/id/key
	* post type

Command:
	Sanitize String
Data:
	* key
	* type

Command:
	Display Meta Box
Data:
	* id
	* label String
	* context
	* priority
	* FieldTemplates [

	]