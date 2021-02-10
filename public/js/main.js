/**
 * Created by Marshall.D.Teach on 26/03/2020.
 */

$(document).ready(function(){
    $("#header-1").sticky({topSpacing:0, zIndex: '50'});

    if($(window).width() > 768){
        $(".dropdown-toggle").click(function(){
            if($(this).parent('li').siblings('li.dropdown').children('ul.dropdown-menu').is(':visible')){
                $(this).parent('li').siblings('li.dropdown').children('ul.dropdown-menu').slideUp('fast');
            }
            $(this).next('ul').slideToggle('medium');
        });
    }

    if($(window).width() <= 768){
        $('li > ul').removeClass('dropdown-menu');
        $('li > ul').hide();
        $('.dropdown-toggle').click(function(){
            if($(this).parent('li').siblings('li').children('ul').is(':visible')){
                $(this).parent('li').siblings('li').children('ul').slideUp('medium');
            }
            $(this).next('ul').slideToggle('medium');
        });
    }

    $('.nav-toggler').click(function(e) {
        e.preventDefault();
        $('.nav-toggler-icon').toggleClass('fa-times fa-bars');
        $('.responsive-nav').toggleClass('active');
    });

    // SEARCH BAR
    $('.search-toggler').click(function(e) {
        e.preventDefault();
        $('.search-toggler-icon').toggleClass('fa-times fa-search');
        $('.logo').toggleClass('d-block d-none');
        $('#header-1').toggleClass('py-3 py-0');
        $('.search-form').toggleClass('d-block d-none');
    });
    $('.search-input').blur(function(){
        $('.logo').toggleClass('d-block d-none');
        $('#header-1').toggleClass('py-3 py-0');
        $('.search-toggler-icon').toggleClass('fa-times fa-search');
        $('.search-form').toggleClass('d-block d-none');
    });

    // CAROUSEL
    $('.products-slide .owl-carousel').owlCarousel({
        loop:false,
        nav:false,
        navText:["<i class='fas fa-angle-left fa-2x'></i>", "<i class='fas fa-angle-right fa-2x'></i>"],
        margin:10,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true,
                dots:false
            },
            576:{
                items:2
            },
            768:{
                items:3
            },
            992:{
                items:4
            }
        },
        smartSpeed: 1000
    });

    // BACK TO TOP
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#back-to-top').fadeIn('slow');
        } else {
            $('#back-to-top').fadeOut('slow');
        }
    });
    $('#back-to-top').click(function(){
        $('html, body').animate({scrollTop : 0},500);
        return false;
    });

    // SHOW PASSWORD
    $('button[data-action=show-pwd]').click(function(){

        var input = $('#registration_password');

        if(input.attr('type') == 'password'){
            input.attr('type', 'text');
        }else{
            input.attr('type', 'password');
        }

        $('.eye-icon').toggleClass('fa-eye fa-eye-slash');
    });

    $('.form-error-icon').text('erreur');
});
