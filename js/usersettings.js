/**
 * This file contains the javascript function of the usersettings.
 */
$(document).ready(function(){
   /**
    *  Getting the userdata from the user in #user_id is selected
    *  @TODO make a other function in php for the getUserData
    */
   $('#changeUser_user_id').change(function(){
      $.post("include/back_get_user_data.php",$("#changeUser_user_id").serialize(),function(msg){
          var msg_decode = JSON.parse(msg);
          var data = msg_decode[0];
          $('#changeUser_id').val(data["id_user"]);
          $('#changeUser_username').val(data["user_name"]);
          $('#changeUser_first_name').val(data["first_name"]);
          $('#changeUser_last_name').val(data["last_name"]);
          $('#changeUser_tele').val(data["telephone"]);
          $('#changeUser_gb').val(data["geburtsdatum"]);
          $('#changeUser_email').val(data["email"]);
          $('#changeUser_rights').val(data["rights"]);
          $('#changeUser_abzeichen').val(data["abzeichen"]);
          $('#changeUser_med').val(data["med"]);
          });
          //end post()
   });
   //end change()
});
//end ready()