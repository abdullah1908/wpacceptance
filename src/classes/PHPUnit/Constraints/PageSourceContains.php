<?php
/**
 * Test page source contents constraint
 *
 * @package  wpacceptance
 */

namespace WPAcceptance\PHPUnit\Constraints;

use WPAcceptance\Utils;

/**
 * Constraint class
 */
class PageSourceContains extends \WPAcceptance\PHPUnit\Constraint {

	/**
	 * The text to look for.
	 *
	 * @access private
	 * @var string
	 */
	private $text;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $action The evaluation action. Valid options are "see" or "dontSee".
	 * @param string $text A text to look for.
	 */
	public function __construct( $action, $text ) {
		parent::__construct( $action );
		$this->text = $text;
	}

	/**
	 * Evaluate if the actor can or can't see a text in the page source.
	 *
	 * @access protected
	 * @param \WPAcceptance\PHPUnit\Actor $other The actor instance.
	 * @return boolean TRUE if the constrain is met, otherwise FALSE.
	 */
	protected function matches( $other ): bool {
		$actor = $this->getActor( $other );

		$content = trim( $actor->getPageSource() );
		if ( empty( $content ) ) {
			// if current action is "dontSee" then return "true" what means the constrain is met,
			// otherwise it means that action is "see" and the constrain isn't met, thus return "false"
			return self::ACTION_DONTSEE === $this->action;
		}

		$found = Utils\find_match( $content, $this->text );

		return ( $found && self::ACTION_SEE === $this->action ) || ( ! $found && self::ACTION_DONTSEE === $this->action );
	}

	/**
	 * Return description of the failure.
	 *
	 * @access public
	 * @return string The description text.
	 */
	public function toString(): string {
		return sprintf( ' "%s" text in the page source', $this->text );
	}

}
