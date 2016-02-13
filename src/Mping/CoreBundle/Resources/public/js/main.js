function showFlash(msg)
{
    $('.flash-notice').remove();
    $('<div class="flash-notice"><p>'+msg+'</p></div>').appendTo('body');
    _showFlash();
}

function _showFlash() {
    $toast = $('.flash-notice'); 
    $toast
        .addClass('ui-body-b ui-overlay-shadow ui-corner-all')
        .css('margin-left', '-'+($toast.width()/2)+'px')
        .fadeIn(400)
        .delay(4500)
        .fadeOut(400, function() {
            $(this).remove();
        });
}

$(document).on('pagebeforeshow', function () {
    $.mobile.activePage.find(".ui-header .ui-toolbar-back-btn")
        .addClass("ui-btn-icon-notext")
        .addClass("ui-icon-arrow-l")
        .removeClass("ui-btn-icon-left")
        .removeClass("ui-icon-carat-l");
});

$(document).on('pagecontainershow', function(e, ui) {
    setTimeout(function() {
        _showFlash();
    }, 500);
    
    $('.graphic-pie').each(function() {
        var canvas = $(this).get(0);
        var context = canvas.getContext("2d");
        var data = $(this).data('pie-data').split(',');
        var colors = $(this).data('pie-color').split(',');
        
        var centerX = 50;
        var centerY = 50;
        var radius = 50;
        
        var i;
        var total = 0;
        
        for (i = 0; i < data.length; i++) {
            total += parseInt(data[i]); 
        }
        
        var sum = 0;
        
        context.lineWidth = 2;
        context.lineCap='round';
        context.strokeStyle = '#F9F9F9';

        var startAngle, endAngle;

        for (i = 0; i < data.length; i++) {
            startAngle = Math.PI*2 * sum / total - Math.PI/2;
            endAngle = startAngle + (Math.PI*2 * parseInt(data[i]) / total);
            
            context.beginPath();
            context.moveTo(centerX, centerY);
            context.arc(centerX, centerY, radius, startAngle, endAngle, false);
            context.closePath();

            context.fillStyle = colors[i];
            context.fill();
            
            context.beginPath();
            context.moveTo(centerX, centerY);
            context.lineTo(centerX + (radius+2)*Math.cos(startAngle), centerY + (radius+2)*Math.sin(startAngle));
            context.stroke();
            
            context.beginPath();
            context.moveTo(centerX, centerY);
            context.lineTo(centerX + (radius+2)*Math.cos(endAngle), centerY + (radius+2)*Math.sin(endAngle));
            context.stroke();
            
            sum += parseInt(data[i]);
        }
    });
});

$(document).on('change', '.licences-promo', function(e, ui) {
    e.preventDefault();

    var $form = $(this).parents('form');

    $form.get(0).submit();
});

$(document).on('click', '.add-favorite', function(e, ui) {
    e.preventDefault();
    
    $button = $(this);
    
    $.ajax($button.data('url'), {
        type: 'POST',
        dataType: 'json',
        data: {
            type: $(this).data('favorite-type'),
            id: $(this).data('favorite-id'),
            name: $(this).data('favorite-name'),
        }
    }).done(function(data) {
        showFlash(data.message);
        $button.toggleClass('ui-alt-icon');
    });
});

$(document).on('click', '.remove-favorite', function(e) {
    e.preventDefault();
    
    var favoriteId = $(this).data('favorite-id');
    
    $.ajax($(this).data('url'), {
        type: 'POST',
        dataType: 'json',
        data: {
            id: favoriteId,
        }
    }).done(function(data) {
        var $li = $('li[data-favorite-id="'+favoriteId+'"]');
        var $ul = $li.parent();
        
        $li.remove();
        $ul.listview('refresh');
        
        showFlash(data.message);
    });
});