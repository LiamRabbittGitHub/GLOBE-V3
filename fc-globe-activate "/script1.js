jQuery(document).ready(function(){
    jQuery('.check').click(function() {
        jQuery('.check').not(this).prop('checked', false);
    });
    jQuery('#save-alert').click(function() {
        jQuery(".fc-saveConfirm").show();
    });
     jQuery('.fc-saveConfirm').click(function() {
        jQuery(this).hide();
    });
 });

if (window.location.pathname == '/activate/') {
    var input = document.getElementById('map');
    var autocomplete = new google.maps.places.Autocomplete(input);
}

