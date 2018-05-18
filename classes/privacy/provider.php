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
 * Privacy Subsystem implementation for block_course_overview.
 *
 * @package    block_course_overview
 * @copyright  2018 Jan Dageförde
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_course_overview\privacy;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../../locallib.php');
/**
 * Privacy Subsystem for block_course_overview implementing the metadata provider and the user preference provider.
 *
 * @copyright  2018 Jan Dageförde
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider,
    \core_privacy\local\request\user_preference_provider {

    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores user preferences.
     *
     * @return  string
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_user_preference('number_of_courses',
            'privacy:metadata:preference:number_of_courses');
        $collection->add_user_preference('course_sortorder',
            'privacy:metadata:preference:course_sortorder');
        return $collection;
    }

    /**
     * Provides the functionality to export user preferences.
     * @param $userid
     * @throws \coding_exception
     */
    public static function export_user_preferences(int $userid) {
        if ($value = get_user_preferences('course_overview_number_of_courses', null, $userid)) {
            writer::export_user_preference(
                'block_course_overview',
                'number_of_courses',
                $value,
                get_string('privacy:metadata:preference:number_of_courses')
            );
        }
        if ($value = get_user_preferences('course_overview_course_sortorder', null, $userid)) {
            writer::export_user_preference(
                'block_course_overview',
                'course_sortorder',
                $value,
                get_string('privacy:metadata:preference:course_sortorder')
            );
        }
        // If there is nothing in sortorder, there may be something in the old location (identical data, but different format).
        if ($value = get_user_preferences('course_overview_course_order', null, $userid)) {
            writer::export_user_preference(
                'block_course_overview',
                'course_sortorder',
                $value,
                get_string('privacy:metadata:preference:course_sortorder')
            );
        }
    }
}