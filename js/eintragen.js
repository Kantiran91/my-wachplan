/**
 * wachplan - javaScript
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @copyright Copyright (c) 2016, Sebastian Friedl
 * @license AGPL-3.0
 */


function eintragen(data) {
	$.post("include/back_get_row.php", {
		day : data
	}, change_site);

}

function change_site(data) {
	$('body').append(data);
}

function delete_ac(data) {
	$.get("include/back_delete_acc.php", {
		acc : data
	}, change_window);
}
 
function change_window(data){
	window.location="index.php";
}

function add_ac(position,day) {
	$.get("include/back_get_user.php",{
		position : position,
		day : day
	},function(html){
		$('body').append(html);
	});
}
