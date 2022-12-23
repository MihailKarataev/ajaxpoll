
$ = jQuery;
$(document).ready(function(){
    $('.interview__statistic-view').on('click', function(){
        let poll_id = $(this).data('id_poll');
        var button_status = document.getElementById("interview__statistic_"+poll_id);
        if (button_status.style.display === "none") {
            $('#interview__statistic_'+poll_id).slideDown();
            $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
        } else {
            $('#interview__statistic_'+poll_id).slideUp();
            $('.interview__statistic-view_'+poll_id).css("transform", "rotate(0deg)");
        }       
    });
    $('.button').on('click', function(){
        let button_id = $(this).data('button_id'),
            poll_id = $(this).data('poll_id');
        $.ajax({
            url: '/wp-json/synagogues/v1/poll',
            type: 'POST',
            data: {
                button_id: button_id,
                poll_id: poll_id
            },
            success: function (data) {
                $('#interview__button_'+poll_id).css("display", "none");
                $('#interview__statistic_'+poll_id).slideDown();
                $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
                $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
                let total = 0;
                data.forEach(function callback(currentValue, index, data) {
                    $('#'+currentValue['index']+"_"+poll_id).css("width", currentValue['percents']);
                    $('#'+currentValue['index']+"_"+poll_id).html(currentValue['percents']);
                    
                    
                });
                
            }
        });
    });
});

