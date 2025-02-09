@php
    
    $bodyScript2 = getSEOscripts('before_body');
    if (!empty($bodyScript2)) {
        foreach ($bodyScript2 as $v) {
            echo html_entity_decode($v->script_code, ENT_QUOTES);
        }
    }
    
@endphp


<div class="ar-cp">
    <div class="container">
        <div class="row">
            <div class="col-md-10 txtc">
                We use cookies to improve your experience on our website. By using our site you agree to <a
                    class="cplink" href="https://www.multotec.com/en/privacy-policy-and-paia-act">Cookies Policy</a>
            </div>
            <div class="col-md-2">
                <a href="javascript:;" id="Agree" class="cpbtn btn btn-xs btn-primary">Agree</a>
                <a href="javascript:;" id="Disagree" class="cpbtn btn btn-xs btn-danger">Dismiss</a>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/front_end/js/owl.carousel.js') }}"></script>
<!-- menu Js -->
<script type="text/javascript" src="{{ asset('public/front_end/js/menuzord.js') }}"></script>

<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/additional-methods.min.js') }}"></script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/front_end/js/arjs.js') }}"></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js'></script>
<script>
    $(document).ready(function($) {
        $('.slick.marquee').slick({
            speed: 5000,
            autoplay: true,
            autoplaySpeed: 0,
            centerMode: true,
            cssEase: 'linear',
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: true,
            infinite: true,
            initialSlide: 1,
            arrows: false,
            buttons: false
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        if (getCookie("multo_sitecp") == '') {
            $('.ar-cp').show();
        }
        $('.togsrcbtn').on('click', function() {
            $('.searchtoggle').slideToggle();
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.showhide', function() {
            $(this).removeClass('showhide').addClass('showhide2');
        });
        $('body').on('click', '.showhide2', function() {
            $(this).removeClass('showhide2').addClass('showhide');
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        function alignModal() {
            var modalDialog = $(this).find(".modal-dialog");
            /* Applying the top margin on modal dialog to align it vertically center */
            modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
        }
        // Align modal when it is displayed
        $(".modal").on("shown.bs.modal", alignModal);

        // Align modal when user resize the window
        $(window).on("resize", function() {
            $(".modal:visible").each(alignModal);
        });
		/** Append notes on each form*/
        let notes ="<div class='col-md-12 col-sm-12' ><div class='form-group' style='margin-bottom: 0px !important;'><p style='font-size:13px!important; '><strong>Note</strong> : No job applications will be accepted via the form. Please click <a href='https://www.careers24.com/now-hiring/9744-multotec-pty-ltd/' target='_blank;' data-kmt='1'>here</a> to view Multotec current vacancies.</div></div>";

        // $('.ar_vali_class ').prepend(notes);
        $('#field_19').prepend(notes);
        $('.dsk-modal-frm #field_19').before('<div class="row fd_box">'+notes+'</div>');
        $('#field_19,.dsk-modal-frm #field_19').find('br').hide();
    });
</script>

<script>
    $(function() {
        $('.cpbtn').on('click', function() {
            var _cpID = $.trim($(this).attr('id'));
            setCookie('multo_sitecp', _cpID, 365);
            $('.ar-cp').fadeOut(600);
        });
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
</script>

@stack('page_js')

</body>

</html>
