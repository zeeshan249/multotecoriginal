<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                @if (\Request::route()->getName()=='demoDev')
                @include('front_end.includes.sticky_footer_demo')
                    
                @else
                @include('front_end.includes.sticky_footer')
                    
                @endif
                <div class="footer-social">
                    <span class="hidex">Follow Us</span>
                    <ul>
                        @if(isset($socialLinks))
                            @foreach($socialLinks as $scl)
                            
                                @if( $scl->link != '' )
                                    @if (strpos($scl->link, 'facebook') !== false)
                                    <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-facebook" aria-hidden="true"></i></a></li> 
                                    @elseif (strpos($scl->link, 'linkedin') !== false)
                                    <li><a class="linkedin" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-linkedin" aria-hidden="true"></i></a></li> 
                                    @elseif (strpos($scl->link, 'twitter') !== false)
                                    <li><a class="twitter" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-twitter" aria-hidden="true"></i></a></li> 
                                    @elseif (strpos($scl->link, 'youtube') !== false)
                                    <li><a class="youtube" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-play" aria-hidden="true"></i></a></li> 
                                    @elseif (strpos($scl->link, 'instagram') !== false)
                                    <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-instagram" aria-hidden="true"></i></a></li> 
                                    @elseif (strpos($scl->link, 'google') !== false)
                                    <li><a class="youtube" href="{{ $scl->link }}" target="_blank">
                                        <i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
                                    @else
                                    <li><a class="facebook" href="{{ $scl->link }}" target="_blank">
                                        <i class="{{ $scl->icon_css_class }}" aria-hidden="true"></i></a></li>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <ul class="footer-link">
                    <li><a id="toTop" class="backtotop" href="javascript:;"><i class="fa fa-arrow-up" aria-hidden="true"></i> <span class="hide">Back to top</span></a></li>
                    <li class="hidex"><a class="backtohome" href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> Back to Home</a></li>
                </ul>
            </div>
            <div class="col-sm-3 hidex" >
                <div class="footer-link">
                    <a class="contact" href="https://multotec.com/en/contact-multotec"><i class="fa fa-phone" aria-hidden="true"></i> Contact Multotec</a>
                </div>
            </div>
        </div>
    </div>
    @include('front_end.includes.footer_lower')
</footer>