const toast = document.querySelector('#liveToast');

if (toast) {
    toast.classList.add('flex')
    setTimeout(function (){
        toast.classList.add('hidden')
        toast.classList.remove('flex')
    }, 3000);
}
