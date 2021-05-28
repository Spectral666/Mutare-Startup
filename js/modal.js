$(document).ready(function(){

    var registrate_btn = $('#registrate-btn'),
        inicio_btn = $('#inicio-btn');


    function ocultarModalClick(objeto){
        $('body').click(function(e){
            if(e.target.classList[0] == 'modal'){
                objeto.removeClass('active');
            }
        });
    }

    function comprobarActive(objeto, modal){

        if(objeto[0].classList[1] != 'active'){
            modal.addClass('active')
        }
    }

    registrate_btn.on('click', function(){
        var modal_registrate = $('#registrate');


        comprobarActive($('#inicio-sesion'), modal_registrate);
        ocultarModalClick(modal_registrate);
    })

    inicio_btn.on('click', function(){
        var modal_inicio_sesion = $('#inicio-sesion');

        comprobarActive($('#registrate'), modal_inicio_sesion);
        ocultarModalClick(modal_inicio_sesion);
       
        
    });




});