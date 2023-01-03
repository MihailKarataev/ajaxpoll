
$ = jQuery;
$(document).ready(function(){
    $('.interview__statistic-view').on('click', function(){//показ статистики по нажатию кнопки
        let poll_id = $(this).data('poll_id');
        var button_status = document.getElementById("interview__statistic_"+poll_id);
        if (button_status.style.display === "none") {
            $('#interview__statistic_'+poll_id).slideDown();
            $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
        } else {
            $('#interview__statistic_'+poll_id).slideUp();
            $('.interview__statistic-view_'+poll_id).css("transform", "rotate(0deg)");
        }       
    });
    $('.button').on('click', function(){//считывание нажатия на кнопку голосования
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
                console.log(data);
                $('#interview__button_'+poll_id).slideUp();
                $('#interview__statistic_'+poll_id).slideDown();
                $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
                $('.interview__statistic-view_'+poll_id).css("transform", "rotate(180deg)");
                let total = data['set']['total']+1,
                    width_item = 0;
                   
                $.each(data, function( index, value ) {
                    if(!Array.isArray( value )){
                        
                    
                        if(value !== 0){
                            width_item = ((value / total) * 100).toFixed(2);
                        }else{
                            width_item = 0;
                        }
                        console.log(width_item+"-ширина"+total+"-total");
                    
                        $('#'+poll_id+'_'+index).css("width", "0");
                        $('#'+poll_id+'_'+index).animate({width: + width_item + "%", }, 1500);
                        $('#'+poll_id+'_'+index).html(index + '-' + value + '(' + width_item + '%)');
                    }
                    
                });   
                
                
            }
        });
    });
});

