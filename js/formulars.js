$(document).ready(function(){
	
	//formular anmeldung ../anmeldung/anmeldung.php
	$('#anmeldung').submit(function(e){
		e.preventDefault();
		$.post("backend_anmeldung.php",$("#anmeldung").serialize(),function(msg){
			 $("#anmeldung").hide().load("message.html").fadeIn(2000);
            })
       return false;
	})
	
	//formular register for new wp ../intern/index.php 
	$('#registeNewSaison').submit(function(e){
		e.preventDefault();
		$.post("include/back_registe_user.php",$("#registeNewSaison").serialize(),change_site)
       return false;
	})
	
	// if someone forgot the password ../pw_vergessen.php
	$('#pw_forgot').submit(function(e){
		e.preventDefault();
		$.get("include/new_passwort.php",$("#pw_forgot").serialize(),change_site)
       return false;
	})
	
	// formular new day ../intern/system_settings.php
	$('#new_day').submit(function(e){
		e.preventDefault();
		$.post("include/back_add_day.php",$("#new_day").serialize(),change_site)
       return false;
	})
	
	// formular more new days ../intern/system_settings.php
	$('#more_days').submit(function(e){
		e.preventDefault();
		$.post("include/back_add_more_days.php",$("#more_days").serialize(),change_site)
       return false;
	})
	
	// formular change user data ../intern/system_settings.php
	$('#change_user_data').submit(function(e){
		e.preventDefault();
		$.post("include/back_change_user_data.php",$("#change_user_data").serialize(),change_site)
       return false;
	})
	
	// formular get user data ../intern/system_settings.php
	//This function get the user_data an put it on the change formular
	$('#user_id').change(function(e){
		$.post("include/back_get_user_data.php",$("#user_id").serialize(),function(msg){
			$('#c_eh').prop('checked', false);
			$('#c_san').prop('checked', false);
			 msg_decode = JSON.parse(msg);
			 data = msg_decode[0];
			 $('#c_id').val(data["id_user"]);
			 $('#c_user').val(data["user_name"]);
			 $('#c_first').val(data["first_name"]);
			 $('#c_last').val(data["last_name"]);
			 $('#c_tele').val(data["telephone"]);
			 $('#c_gb').val(data["geburtsdatum"]);
			 $('#c_email').val(data["email"]);
			 $('#c_rights').val(data["rights"]);
			 $('#c_abzeichen').val(data["abzeichen"]);
			 if(data["med"] =="EH"){
				 $('#c_eh').prop('checked', true);
			 }
			 if(data["med"] =="san"){
				 $('#c_san').prop('checked', true);
			 }
			 
			 })
	})

});