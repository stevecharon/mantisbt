<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A base class for intent based commands that can accomplish a task.
 * Such commands will be used from web ui action pages, REST API, SOAP API, etc.
 * This provides consistency across such callers while being agnostic of the
 * caller.
 *
 * The command pattern will build on top of model, APIs, configurations, and
 * authorization.
 */
abstract class Command
{
	/**
	 * @var array The input data for the command.
	 */
	protected $data;

	/**
	 * Command constructor taking in all required data to execute the command.
	 *
	 * @param array $p_data The command data.
	 */
	function __construct( array $p_data ) {
		$this->data = $p_data;
	}

	/**
	 * Make sure that provided data is valid and update data with default
	 * value that are missing.
	 *
	 * @return void
	 */
	abstract protected function validate();

	/**
	 * The core execution of the command (e.g. assigning an issue) with no knowledge
	 * about side effects of such execution, e.g. an email will be sent via a post processor.
	 *
	 * @return array The results of the command processing that goes into $executionResults
	 */
	abstract protected function process();

	/**
	 * Execute the command.  This may throw a CommandException is execution is interrupted.
	 * The command is expected to trigger events that are handled by plugins as part of
	 * exection.
	 *
	 * @return array Execution result
	 */
	public function execute() {
		# For now, all commands require user to be authenticated
		auth_ensure_user_authenticated();		

		$this->validate();
		return $this->process();
	}
}