$(function() {
    modalConfig();
});

function modalConfig() {
    // this function simply configures modal properties such as making backdrop static or something...
    $('#mod_queue').modal({
        backdrop: 'static',
        keyboard: false
    });
}
