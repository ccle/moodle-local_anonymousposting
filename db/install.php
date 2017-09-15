<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Install utility.
 *
 * @package    local
 * @subpackage anonymousposting
 * @copyright  2011 Juan Leyva <juanleyvadelgado@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Set basic plugin settings.
 */
function xmldb_local_anonymousposting_install() {
    global $DB;

    set_config('enabled' , 1, 'local_anonymousposting');

    // Install Anonymous user if not already installed.
    if (!$anonroleid = $DB->record_exists('role', array('shortname' => 'anonstudent'))) {
        $anonroleid = create_role(get_string('anonstudentrole', 'local_anonymousposting'),
                'anonstudent', get_string('anonstudentroledesc', 'local_anonymousposting'));
        set_role_contextlevels($anonroleid, array(CONTEXT_COURSE));
        $context = context_system::instance();

        // Add only necessary forum capabilities.
        assign_capability('mod/forum:createattachment', CAP_ALLOW, $anonroleid, $context->id);
        assign_capability('mod/forum:replypost', CAP_ALLOW, $anonroleid, $context->id);
        assign_capability('mod/forum:startdiscussion', CAP_ALLOW, $anonroleid, $context->id);
        assign_capability('mod/forum:viewdiscussion', CAP_ALLOW, $anonroleid, $context->id);
    }

    // Set Default course role and activity role to anonstudent.
    set_config('defaultcourserole' , $anonroleid, 'local_anonymousposting');
    set_config('defaultactivityrole' , $anonroleid, 'local_anonymousposting');
}
