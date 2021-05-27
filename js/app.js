function navbarScroll(){

    if($(window).scrollTop() <= 785){
        $('#navbar').removeClass('activeScroll');
    }else{
        $('#navbar').addClass('activeScroll');
    }
        
    
}

if($(location).attr('href') == "file:///home/adriangcoding/Escritorio/MUTARE/index.html"){
    $(window).on('scroll', navbarScroll);
    $(window).on('load', navbarScroll);
}else{
    $('#navbar').addClass('activeScroll');
    
}

$('#btn-recibir').click(function(){

    $('html').animate({scrollTop: $('#contenedor').offset().top - 160}, 1000);

});

$('#imagen').bind('change', function() { 
    var fileName = ''; fileName = $(this).val(); $('#file-selected').html(fileName); 
})