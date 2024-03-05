var toast = document.getElementById('liveToast');

if (toast) {
    $('#liveToast').show();
    setTimeout(function (){
        $('#liveToast').hide();
    }, 3000);
}